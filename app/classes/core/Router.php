<?php

if ( !defined( 'APP_VERSION' ) ) {
    exit();
}

class Router {

    /** @const string */
    const DEFAULT_ADMIN_CONTROLLER = 'admin';
    
    /** @var array */
    static $ROUTES = array();
    
    /** @var string */
    static $PATH;

    /**
     * Router init function..
     * 
     * @return void
     */
    public static function init() {

        // store paths
        self::$PATH = isset( $_REQUEST[ '__path__' ] ) ? $_REQUEST[ '__path__' ] : null;
        self::$ROUTES = explode( '/', self::$PATH );
        
        // unset last route part if is empty (user just put / on the end of url)
        if( !strlen( trim( self::$ROUTES[ count(self::$ROUTES) - 1 ] ) ) ){
            
            unset( self::$ROUTES[ count(self::$ROUTES) - 1 ] );
        }
        
        // custom script functions
        if ( count( self::$ROUTES ) >= 2 && self::$ROUTES[ 0 ] == 'script' ) {

            $class_name = '';
            foreach ( explode( '-', self::$ROUTES[ 1 ] ) as $part ) {
                $class_name .= ucfirst( $part );
            }

            if ( file_exists( APP_DIR . '/classes/scripts/' . $class_name . '.php' ) ) {
                require_once APP_DIR . '/classes/scripts/' . $class_name . '.php';

                if ( method_exists( $class_name, 'do' ) ) {

                    $class_name::do();
                }
            }
        }

        // do.. funcitons
        if ( count( self::$ROUTES ) >= 2 && preg_match( '/^do/um', self::$ROUTES[ 0 ] ) ) {

            $class_name = ucfirst( substr( self::$ROUTES[ 0 ], 2 ) );
            if ( class_exists( $class_name ) && method_exists( $class_name, self::$ROUTES[ 1 ] ) ) {

                $tmp_class = new $class_name;
                $tmp_class::{self::$ROUTES[ 1 ]}();
            }
        }

        // basic tenant routing.. 
        if ( isset( self::$ROUTES[ 0 ] ) && !defined( 'ADMIN_DIR' ) ) {
            App::$TEENANT = self::$ROUTES[ 0 ];
        }

        // admin routes..
        if ( defined( 'ADMIN_DIR' ) ) {

            // check if user is signed in
            User::checkLogged();
            
            // initialize admin menu
            Admin::menuInit();
    
            // do the magic work powerfull wizard..
            if ( count( self::$ROUTES ) >= 2  &&
                    method_exists( ucfirst( self::$ROUTES[ 0 ] ), 'action' . ucfirst( self::$ROUTES[ 1 ] ) . '_admin' ) ) {
                
                ucfirst( self::$ROUTES[ 0 ] )::{'action' . ucfirst( self::$ROUTES[ 1 ] ) . '_admin'}();
            }
            elseif( count( self::$ROUTES ) == 1 && 
                method_exists( ucfirst( self::$ROUTES[ 0 ] ), 'actionIndex_admin' ) ) {

                ucfirst( self::$ROUTES[ 0 ] )::actionIndex_admin();
            }
            else{
                
                ucfirst( self::DEFAULT_ADMIN_CONTROLLER )::actionIndex_admin();
            }
        }
    }

}
