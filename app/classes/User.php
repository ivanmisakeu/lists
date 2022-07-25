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
    
    /* ------- ** TEMPLATE FUNCTIONS ** ------- */

    public static function actionIndex_admin() {

        // @todo
    }
    
    public static function actionAdd_admin(){
        
        Template::setPageTitle( Lang::l( 'Add new user' ) );
        Template::generate_admin( 'user/add' );
    }

    public static function actionAuth_admin() {

        Template::$FULL_VIEW = true;

        // @todo
    }

    public static function actionLogout_admin() {

        // @todo
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

        return APP::$DB->query( $sql, $mail )->fetchSingle();
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

}
