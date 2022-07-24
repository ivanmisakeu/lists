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
        Template::assign( 'items', Items::getAllByTenantId( $tenant['id'] ) );

        if( $tenant['title'] ){
            Template::setPageTitle( $tenant['title'] );
        }
        
        Template::render( 'item' );
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

}
