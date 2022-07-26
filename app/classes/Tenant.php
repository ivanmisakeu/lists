<?php

if (!defined( 'APP_VERSION' )) {
    exit();
}

class Tenant extends Core {

    const TABLE_NAME = 'tenants';
    const TENANT_ACTIVE = 1;
    const TENANT_DISABLED = 0;

    /* ------- ** TEMPLATE FUNCTIONS ** ------- */

    public static function actionIndex() {

        Template::render( 'tenant' );
    }

    public static function actionIndex_admin() {

        Template::assign( 'tenants', self::getAll() );
        Template::generate_admin( 'tenant/index' );
    }
    
    /* ------- ** DATABASE FUNCTIONS ** ------- */

    /**
     * Returns one row by ID
     * 
     * @param int $id
     * @return array
     */
    public static function get( int $id ){
        
        return parent::_get( $id , self::TABLE_NAME );
    }
    
    /**
     * Returns tenant by his name
     * 
     * @param string $name
     * @return array
     */
    public static function getByName( string $name ) {

        $sql = '
                SELECT 
                    * 
                FROM ' . self::TABLE_NAME . ' 
                WHERE 
                    name = %s
                    AND active = ' . self::TENANT_ACTIVE;

        return APP::$DB->query( $sql, $name )->fetch();
    }

    /**
     * Return tenant by his ID
     * 
     * @param type $id
     * @return array
     */
    public static function getNameById( $id ) {

        $sql = '
                SELECT 
                    name 
                FROM ' . self::TABLE_NAME . ' 
                WHERE 
                    id = ' . (int) $id;

        return APP::$DB->query( $sql )->fetchSingle();
    }
    
    /**
     * Get all tenants list
     * 
     * @return array
     */
    public static function getAll(){
        
        $sql = '
                SELECT 
                    * 
                FROM ' . self::TABLE_NAME . ' 
                WHERE 
                    active = ' . self::TENANT_ACTIVE . ' 
                ORDER BY name ASC';

        return APP::$DB->query( $sql )->fetchAll();
    }

    /**
     * Function checks if tenant with given id exists
     * @note Why the fuck do I need this?
     * 
     * @param int $id
     * @return bool
     */
    public static function checkTenantExists( int $id ) {

        return self::getNameById( $id ) ? true : false;
    }

    /**
     * Create new tenant 
     * @note Only add new row into db, no checking if tenant with given name already exists
     * 
     * @param string $name
     * @return bool
     */
    public static function add( string $name ) {

        return parent::insert( [
                    'name' => $name,
                    'active' => self::TENANT_ACTIVE
                        ], self::TABLE_NAME );
    }

    /* ------- ** ACTION FUNCTIONS ** ------- */

    public static function createTenant() {

        Helper::captcha_verify();

        if (isset( $_POST['name'] ) && strlen( $_POST['name'] )) {

            $tenant_name = Helper::str_clear_tenant_name( ( $_POST['name'] ) );

            // check if given tenant already exists
            if (!strlen( $tenant_name ) || self::getByName( $tenant_name )) {

                Helper::flash_set( Lang::l( 'Tenant already exists' ), Helper::FLASH_DANGER );
                Helper::redirect( APP_URL . '/' );
            }

            // create new tenant
            self::add( $tenant_name );

            Helper::flash_set( Lang::l( 'Tenant has been sucessfully created' ) );
            Helper::redirect( APP_URL . '/' . $tenant_name );
            
        }
        
        Helper::redirect_error_home();
    }
    
    public static function configList(){
        
        Helper::captcha_verify();
        
        // check tenant at first
        if( isset( $_POST['tenant_id'] ) ){
            
            $tenant = self::get( (int) $_POST['tenant_id'] );
            if( !$tenant ){
            
                Helper::flash_set( Lang::l( 'Tenant does not exists' ), Helper::FLASH_DANGER );
                Helper::redirect( APP_URL . '/' );
            }
        }
       
        // here we go..
        if (isset( $_POST['tenant_name'] ) && strlen( $_POST['tenant_name'] )) {
            
            $title = Helper::str_remove_html( $_POST['tenant_name'] );
            $title = trim( $title );
  
            App::$DB->query('UPDATE ' . self::TABLE_NAME . ' SET', [
                'title' => $title,
            ], 'WHERE id = ?', (int) $tenant['id'] );
            
            Helper::flash_set( Lang::l( 'Settings has been saved' ) );
            Helper::redirect( APP_URL . '/' . $tenant['name'] );
        }
        
        Helper::redirect_error_home();
    }

}
