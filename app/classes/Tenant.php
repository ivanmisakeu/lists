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

        Template::assign( 'tenants', self::getAll( false ) );
        Template::generate_admin( 'tenant/index' );
    }
    
    public static function actionAdd_admin() {

        Template::generate_admin( 'tenant/add' );
    }
    
    public static function actionEdit_admin(){
        
        if( !isset( Router::$ROUTES[2]) ){
           
            Helper::redirect_error_home();
        }
        
        $tenant = self::get( (int) Router::$ROUTES[2] );
        
        if( !$tenant ){
            
            Helper::redirect_error_home();
        }
        
        Template::assign( 'tenant', $tenant );
        Template::generate_admin( 'tenant/edit' );
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
     * @param bool $active_only (opt.)
     * @return array
     */
    public static function getAll( bool $active_only = true ){
        
        $sql = '
                SELECT 
                    * 
                FROM ' . self::TABLE_NAME;
        
        if( $active_only ){
            
            $sql .= ' WHERE active = ' . self::TENANT_ACTIVE;
        }
        
        $sql .= ' ORDER BY name ASC';

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
     * @param string $name - address of the list
     * @param string $title (opt.) - name of the list to display
     * @return bool
     */
    public static function add( string $name , string $title = null ) {

        return parent::insert( [
                    'name' => $name,
                    'title' => $title,
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

                Helper::flash_set( Lang::l('Tenant with given link already exists') , Helper::FLASH_DANGER );
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
    
    public static function disableTenant(){
        
        Helper::captcha_verify();
        
        if( !isset( $_POST['id_tenant'] ) ){
            Helper::redirect_error_home();
        }
        
        $tenant = Tenant::get( $_POST['id_tenant'] );
        
        if( !$tenant || $tenant['active'] != self::TENANT_ACTIVE ){
            
            Helper::flash_set( Lang::l('List not found') , Helper::FLASH_DANGER );
            Helper::redirect_to_posted_url();
        }
        
        $update_data = array(
            'active' => self::TENANT_DISABLED
        );
        
        App::$DB->query('UPDATE ' . self::TABLE_NAME . ' SET', $update_data , 'WHERE id = ?', (int) $tenant['id'] );
        
        Helper::flash_set( Lang::l('List has been disabled') );
        Helper::redirect_to_posted_url();
        
    }
    
    public static function enableTenant(){
        
        Helper::captcha_verify();
        
        if( !isset( $_POST['id_tenant'] ) ){
            Helper::redirect_error_home();
        }
        
        $tenant = Tenant::get( $_POST['id_tenant'] );
        
        if( !$tenant || $tenant['active'] != self::TENANT_DISABLED ){
            
            Helper::flash_set( Lang::l('List not found') , Helper::FLASH_DANGER );
            Helper::redirect_to_posted_url();
        }
        
        $update_data = array(
            'active' => self::TENANT_ACTIVE
        );
        
        App::$DB->query('UPDATE ' . self::TABLE_NAME . ' SET', $update_data , 'WHERE id = ?', (int) $tenant['id'] );
        
        Helper::flash_set( Lang::l('List has been enabled') );
        Helper::redirect_to_posted_url();
    }

    public static function addNewTenant(){
        
        Helper::captcha_verify();
        
        if( !isset( $_POST['name'] ) || !strlen( $_POST['name']) ){
            Helper::redirect_error_home();
        }
        
        $tenant_name = Helper::str_clear_tenant_name( ( $_POST['name'] ) );

        // check if given tenant already exists
        if (!strlen( $tenant_name ) || self::getByName( $tenant_name )) {

            Helper::flash_set( Lang::l('Tenant with given link already exists') , Helper::FLASH_DANGER );
            Helper::redirect( isset($_POST['self_url']) ? $_POST['self_url'] : '' );
        }

        // create new tenant
        self::add( $tenant_name , isset($_POST['title']) ? $_POST['title'] : null );

        Helper::flash_set( Lang::l( 'Tenant has been sucessfully created' ) );
        Helper::redirect_to_posted_url();        
        
    }
    
    public static function editTenant(){
     
        Helper::captcha_verify();
        
        if( !isset( $_POST['name'] ) || !strlen( $_POST['name']) || !isset($_POST['id_tenant']) ){
            Helper::redirect_error_home();
        }

        $tenant = self::get( $_POST['id_tenant'] );
        $tenant_name = Helper::str_clear_tenant_name( ( $_POST['name'] ) );
        $tenant_by_name = self::getByName( $tenant_name );
        
        if( !$tenant  ){
            Helper::redirect_error_home();
        }
        
        if( $tenant_by_name && $tenant_by_name['id'] != $tenant['id'] ){
            
            Helper::flash_set( Lang::l('Tenant with given link already exists') , Helper::FLASH_DANGER );
            Helper::redirect( isset($_POST['self_url']) ? $_POST['self_url'] : '' );
        }
        
        $update_data = ['name' => $tenant_name];
        if( isset($_POST['title']) && strlen($_POST['title']) ){
            $update_data['title'] = $_POST['title'];
        }
        
        App::$DB->query('UPDATE ' . self::TABLE_NAME . ' SET', $update_data , 'WHERE id = ?', (int) $tenant['id'] );
        
        Helper::flash_set( _l('List has been updated') );
        Helper::redirect_to_posted_url();
    }
}
