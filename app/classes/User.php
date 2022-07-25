<?php

if ( !defined( 'APP_VERSION' ) ) {
    exit();
}

class User extends Core {

    const TABLE_NAME = 'users';
    
    const USER_ADMIN = 1;
    const USER_GUEST = 0;
    
    const USER_DELETED = 1;
    const USER_ACTIVE = 0;
    
    public static $CURRENT_USER;
    
    /* ------- ** TEMPLATE FUNCTIONS ** ------- */

    public static function actionIndex_admin() {

        Helper::dd( self::$CURRENT_USER );
        // @todo
    }
    
    public static function actionAdd_admin(){
        
        Template::setPageTitle( Lang::l( 'Add new user' ) );
        Template::generate_admin( 'user/add' );
    }

    public static function actionAuth_admin() {

        // redirect user to homepage if is signed in
        if( self::$CURRENT_USER ){
            
            ucfirst( Router::DEFAULT_ADMIN_CONTROLLER )::actionIndex_admin();
            return;
        }
        
        Template::$FULL_VIEW = true;

        Template::setPageTitle( Lang::l( 'Sign in' ) );
        Template::generate_admin( 'user/auth' );
    }

    public static function actionLogout_admin() {

        if( isset($_SESSION['token'])) {
            unset( $_SESSION['token'] );
        }
        
        Helper::flash_set( Lang::l('You were signed out') );
        Helper::redirect( ADMIN_URL );
    }

    /* ------- ** DATABASE FUNCTIONS ** ------- */

    /**
     * Returns one row by ID
     * 
     * @param int $id
     * @return array
     */
    public static function get( int $id ) {

        return parent::_get( $id, self::TABLE_NAME );
    }

    /**
     * Check if user email is already taken
     * 
     * @param string $mail
     * @return bool
     */
    public static function checkMailExists( string $mail ) {

        return self::getUserByMail( $mail ) ? true : false;
    }

    /**
     * Find user following his email address
     * 
     * @param string $mail
     * @return array
     */
    public static function getUserByMail( string $mail ) {

        $sql = '
                SELECT 
                    * 
                FROM ' . self::TABLE_NAME . ' 
                WHERE 
                    mail = ? ';

        return APP::$DB->query( $sql, $mail )->fetch();
    }
    
    /**
     * Find user following his login token
     * 
     * @param string $token
     * @return array
     */
    public static function getUserByToken( string $token ) {

        $sql = '
                SELECT 
                    * 
                FROM ' . self::TABLE_NAME . ' 
                WHERE 
                    token = ? ';

        return APP::$DB->query( $sql, $token )->fetch();
    }
    
    /**
     * Store generated login token to user in db
     * 
     * @param string $token
     */
    public static function storeToken( string $token ){
        
        App::$DB->query('UPDATE ' . self::TABLE_NAME . ' SET %a', array(
            'token' => $token,
            'updated' => Core::now()
        ));
    }

    /* ------- ** ACTION FUNCTIONS ** ------- */

    public static function addNewUser() {

        Helper::captcha_verify();

        if ( isset( $_POST[ 'mail' ] ) && isset( $_POST[ 'password' ] ) ) {

            if( !Helper::validate_email( $_POST[ 'mail' ] ) ){
               
                Helper::flash_set( Lang::l( 'Email address is not valid' ), Helper::FLASH_DANGER );
                Helper::redirect_to_posted_url();
            }
            
            if ( self::checkMailExists( $_POST[ 'mail' ] ) ) {

                Helper::flash_set( Lang::l( 'User with given email already exists' ), Helper::FLASH_DANGER );
                Helper::redirect_to_posted_url();
            }
            
            if( strlen( $_POST[ 'password' ] ) < 8 ){
                
                Helper::flash_set( Lang::l( 'Password must be at least 8 characters long' ), Helper::FLASH_DANGER );
                Helper::redirect_to_posted_url();
            }

            parent::insert( [
                'name' => Helper::str_nick_from_email( $_POST[ 'mail' ] ),
                'mail' => $_POST[ 'mail' ],
                'password' => Helper::str_hash_password( $_POST[ 'password' ] ),
                'updated' => Core::now()
                    ], self::TABLE_NAME );
            
            Helper::flash_set( Lang::l( 'User has been created' ) );
            Helper::redirect_to_posted_url();
        }

        Helper::redirect_error_home();
    }

    public static function authentificateUser(){
        
        Helper::captcha_verify();

        if ( isset( $_POST[ 'mail' ] ) && isset( $_POST[ 'password' ] ) ) {
            
            $user = self::getUserByMail( $_POST['mail'] );

            if( !$user || $user['password'] != Helper::str_hash_password( $_POST[ 'password' ] ) || $user['deleted'] == 1 ){
                
                Helper::flash_set( Lang::l( 'Authentification failed' ), Helper::FLASH_DANGER );
                Helper::redirect_to_posted_url();
            }
            
            $login_token = Helper::str_generate_uuid();
            
            $_SESSION['token'] = $login_token;
            self::storeToken( $login_token );
            
            Helper::flash_set( Lang::l( 'You were successfully signed-up' ) );
            Helper::redirect_to_posted_url();
        }
        
        Helper::redirect_error_home();
    }
    
    /* ------- ** OTHER FUNCTIONS ** ------- */
    
    /**
     * Check if user is logged, otherwise redirect him to login form
     */
    public static function checkLogged(){
        
        self::$CURRENT_USER = self::getUserByToken( isset($_SESSION['token']) ? $_SESSION['token'] : '' );
        
        if( !self::$CURRENT_USER && Router::$PATH != 'user/auth'){
            
            Helper::redirect( ADMIN_URL . '/user/auth' );
        }
        else if( self::$CURRENT_USER && Router::$PATH == 'user/auth' ){
            
            Helper::redirect( ADMIN_URL );
        }
        
        
    }
}
