<?php

if (!defined( 'APP_VERSION' )) {
    exit();
}

class Items extends Core {

    const TABLE_NAME = 'items';
    const ITEM_DELETED = 1;
    const ITEM_NOT_DELETED = 0;

    /* ------- ** TEMPLATE FUNCTIONS ** ------- */
    
    public static function actionIndex() {

        $tenant = Tenant::getByName( App::$TEENANT );
        
        if( !$tenant ){
            
            Helper::flash_set( Lang::l( 'Tenant does not exists' ), Helper::FLASH_DANGER );
            Helper::redirect( APP_URL . '/' );
        }
        
        Template::assign( 'tenant', $tenant );
        Template::assign( 'items', self::getAllByTenantId( $tenant['id'] ) );

        if( $tenant['title'] ){
            Template::setPageTitle( $tenant['title'] );
        }
        
        Template::render( 'item' );
    }
    
    public static function actionIndex_admin() {

        Helper::redirect( ADMIN_URL . '/tenant' );
    }
    
    public static function actionView_admin() {

        if( !isset(Router::$ROUTES[1]) ){
            
            Helper::redirect( ADMIN_URL . '/tenant' );
        }
        
        $tenant = Tenant::getByName( Router::$ROUTES[2] );
        if( !$tenant ){
            
            Helper::flash_set( Lang::l('Tenant does not exists') , Helper::FLASH_DANGER );
            Helper::redirect( ADMIN_URL . '/tenant' );
        }
        
        Template::assign( 'tenant', $tenant ); 
        Template::assign( 'items', self::getAllByTenantId( $tenant['id'] ) );
        Template::generate_admin( 'items/view' );
    }
    
    public static function actionAdd_admin(){
        
        if( !isset(Router::$ROUTES[2]) ){
            
            Helper::redirect( ADMIN_URL . '/tenant' );
        }
        
        $tenant = Tenant::getByName( Router::$ROUTES[2] );
        if( !$tenant ){
            
            Helper::flash_set( Lang::l('Tenant does not exists') , Helper::FLASH_DANGER );
            Helper::redirect( ADMIN_URL . '/tenant' );
        }
        
        Template::assign( 'tenant', $tenant ); 
        Template::generate_admin( 'items/add' );
    }
    
    public static function actionEdit_admin(){
        
        if( !isset(Router::$ROUTES[2]) ){
            
            Helper::redirect( ADMIN_URL . '/tenant' );
        }
        
        $item = self::get( Router::$ROUTES[2] );
        if( !$item || $item['deleted'] == self::ITEM_DELETED ){
            
            Helper::flash_set( Lang::l('Item does not exists') , Helper::FLASH_DANGER );
            Helper::redirect( ADMIN_URL . '/tenant' );
        }
        
        $tenant = Tenant::get( $item['id_tenant'] );
        if( !$tenant || $tenant['active'] == Tenant::TENANT_DISABLED ){
            
            Helper::flash_set( Lang::l('Tenant does not exists') , Helper::FLASH_DANGER );
            Helper::redirect( ADMIN_URL . '/tenant' );
        }
        
        Template::assign( 'item', $item );
        Template::assign( 'tenant', $tenant ); 
        Template::generate_admin( 'items/edit' );
        
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
     * Get all items from all lists 
     * @note do not display this list on front! never! because little kitty will die..
     * 
     * @return array
     */
    public static function getAll(){
        
        $sql = '
                SELECT 
                    * 
                FROM ' . self::TABLE_NAME . ' 
                WHERE 
                    deleted = ' . self::ITEM_NOT_DELETED . ' 
                ORDER BY created ASC';

        return APP::$DB->query( $sql )->fetchAll();
    }
    
    /**
     * Get all items (not marked as deleted=1) for specified tenant
     * 
     * @param int $id_tenant
     * @return array
     */
    public static function getAllByTenantId( int $id_tenant ) {

        $sql = '
                SELECT 
                    * 
                FROM ' . self::TABLE_NAME . ' 
                WHERE 
                    id_tenant = ' . (int) $id_tenant . ' 
                    AND deleted = ' . self::ITEM_NOT_DELETED . ' 
                ORDER BY created ASC';

        return APP::$DB->query( $sql )->fetchAll();
    }

    /**
     * Create new item for specified tenant
     * 
     * @param string $item_name
     * @param int $id_tenant
     * @return bool
     */
    public static function add( string $item_name, int $id_tenant ) {

        return parent::insert( [
                    'name' => $item_name,
                    'id_tenant' => $id_tenant,
                    'deleted' => self::ITEM_NOT_DELETED
                        ], self::TABLE_NAME );
    }

    /* ------- ** ACTION FUNCTIONS ** ------- */
    
    /**
     * Create new item form process
     */
    public static function createItem() {

        Helper::captcha_verify();

        if (isset( $_POST['name'] ) &&
                isset( $_POST['id_tenant'] ) &&
                Tenant::checkTenantExists( $_POST['id_tenant'] ) &&
                strlen( $_POST['name'] )) {

            // remove special HTML characters
            $name = Helper::str_remove_html( $_POST['name'] );
            
            /*
              if user writes more items in one row comma separated (like my mom did)
              code will break input into more item rows and save them separatly ;-)
             */
            foreach (Helper::str_explode_commas( $name ) as $name) {

                $name = Helper::str_mobile_first_letter( $name );
                self::add( $name, $_POST['id_tenant'] );
            }

            Helper::flash_set( Lang::l( 'Item has been added' ) );
            Helper::redirect( APP_URL . '/' . Tenant::getNameById( $_POST['id_tenant'] ) );
        }
        
        Helper::redirect_error_home();
    }

    /**
     * Remove existing item from list
     * @note Only mark row in db as deleted=1
     */
    public static function removeItem() {

        if (isset( Router::$ROUTES[2] )) {

            $item = parent::_get( Router::$ROUTES[2], self::TABLE_NAME );

            if (!$item) {

                Helper::flash_set( Lang::l( 'Item not found' ), Helper::FLASH_DANGER );
                Helper::redirect( APP_URL . '/' );
            } else {

                App::$DB->query('UPDATE ' . self::TABLE_NAME . ' SET', [
                    'deleted' => self::ITEM_DELETED,
                    'deleted_date' => Core::now()
                ], 'WHERE id = ?', (int) Router::$ROUTES[2] );

                Helper::flash_set( Lang::l( 'Item has been removed' ) );
                Helper::redirect( APP_URL . '/' . Tenant::getNameById( $item['id_tenant'] ) );
            }
        }
        
        Helper::redirect_error_home();
    }
    
    public static function removeItem_admin(){
        
        Helper::captcha_verify();

        if( !isset($_POST['id_item']) || !strlen($_POST['id_item']) ){
            Helper::redirect_error_home();
        }
        
        $item = self::get( $_POST['id_item'] );
        if( !$item || $item['deleted'] == self::ITEM_DELETED ){
            
            Helper::flash_set( Lang::l('Item not found') , Helper::FLASH_DANGER );
            Helper::redirect_to_posted_url();
        }
        
        $tenant = Tenant::get( $item['id_tenant'] );
        if( !$tenant ){
            Helper::redirect_error_home();
        }
        
        $update_data = array(
            'deleted' => self::ITEM_DELETED,
            'deleted_date' => Core::now()
        );
        
        App::$DB->query('UPDATE ' . self::TABLE_NAME . ' SET', $update_data , 'WHERE id = ?', (int) $item['id'] );
        
        Helper::flash_set( Lang::l('Item has been deleted') );
        Helper::redirect( $_POST['redirect_url'] . $tenant['name'] );
    }
        
    public static function editItem(){
        
        Helper::captcha_verify();
        
        if( !isset($_POST['id_item']) || !isset($_POST['id_tenant']) || !isset($_POST['name']) ){
            
            Helper::redirect_error_home();
        }
        
        $item = self::get( $_POST['id_item'] );
        if( !$item || $item['deleted'] == self::ITEM_DELETED ){
            
            Helper::flash_set( Lang::l('Item not found') , Helper::FLASH_DANGER );
            Helper::redirect_to_posted_url();
        }
        
        $tenant = Tenant::get( $item['id_tenant'] );
        if( !$tenant ){
            Helper::redirect_error_home();
        }
        
        $update_data = array(
            'name' => $_POST['name']
        );
        
        App::$DB->query('UPDATE ' . self::TABLE_NAME . ' SET', $update_data , 'WHERE id = ?', (int) $item['id'] );
        
        Helper::flash_set( Lang::l('Item has been updated') );
        Helper::redirect_to_posted_url();
    }

    public static function addItem(){
  
        Helper::captcha_verify();

        if( !isset($_POST['id_tenant']) || !isset($_POST['name']) ){
            
            Helper::redirect_error_home();
        }
        
        $tenant = Tenant::get( $_POST['id_tenant'] );
        if( !$tenant ){
            Helper::redirect_error_home();
        }

        self::add( $_POST['name'] , $tenant['id'] );

        Helper::flash_set( Lang::l('Item has been added') );
        Helper::redirect_to_posted_url();
        
    }
    
}
