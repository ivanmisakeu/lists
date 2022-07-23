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

    /* ------- ** DATABASE FUNCTIONS ** ------- */

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
    }

}
