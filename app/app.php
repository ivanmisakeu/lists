<?php

class App {

    const AUTOLOAD_DIRECTORIES = [ 'classes/core', 'classes' ];
    static $DB = null;
    static $ROUTES = array();
    static $TEENANT = null;
    static $URL_METHOD = null;
    static $translation_array = array();

    public function __construct() {

        // load config
        $this->config_init();
        
        // autoload php classes
        foreach ( self::AUTOLOAD_DIRECTORIES as $autoload_dir ) {
            $this->autoload( $autoload_dir );
        }

        // loading translations if needed
        Lang::init();

        // database initialization
        $this->db_init();

        // router initialization
        $this->router_init();
        
        // check db update script
        if( floatval( Settings::get('APP_VERSION') ) < floatval(APP_VERSION) ){
            Helper::redirect( APP_URL . '/script/db-update?forced' );
        }

    }

    /**
     * Loads config file for current application instance
     * 
     * @throws Exception
     */
    private function config_init(){
        
        $config_file = APP_DIR . '/config/' . gethostname() . '.php';
        
        if( file_exists(APP_DIR . '/config/config.php') && 
            file_exists( $config_file ) ){
            
            require_once APP_DIR . '/config/config.php';
            require_once $config_file;
        }
        else{
            throw new Exception('Missing config file.');
        }
    }
    
    /**
     * Automatically loads all required files for application
     * 
     * @param string $path_to_dir
     */
    private function autoload( string $path_to_dir ) {

        $ignored_paths = [ APP_DIR . '/classes/scripts' ];
        
        foreach ( scandir( APP_DIR . '/' . $path_to_dir ) as $filename ) {

            // ignore some files and directories..
            if ( in_array( $filename, [ '.', '..', '.DS_Store','index.php' ] ) ) {
                continue;
            }
            else if( in_array( APP_DIR . '/' . $path_to_dir . '/' . $filename , $ignored_paths ) ){
                continue;
            }

            if ( is_dir( APP_DIR . '/' . $path_to_dir . '/' . $filename ) ) {
                
                // recursively load subdirectory
                $this->autoload( $path_to_dir . '/' . $filename );
            } else {

                // load php file
                $tmp = explode( '.', $filename );
                if ( strtolower( $tmp[ count( $tmp ) - 1 ] ) == 'php' ) {

                    require_once $path_to_dir . '/' . $filename;
                }
            }
        }
    }

    /**
     * Initialize database connection
     */
    private function db_init() {

        require_once APP_DIR . '/libraries/dibi/loader.php';

        self::$DB = dibi::connect( [
                    'driver' => 'mysqli',
                    'port' => DB_PORT,
                    'username' => DB_USER,
                    'password' => DB_PASSWORD,
                    'database' => DB_NAME,
                ] );
    }

    public static function router_init() {

        if ( isset( $_REQUEST[ '__path__' ] ) ) {

            self::$ROUTES = explode( '/', $_REQUEST[ '__path__' ] );

            // custom script functions
            if ( count( self::$ROUTES ) >= 2 && self::$ROUTES[ 0 ] == 'script' ) {

                $class_name = '';
                foreach( explode('-',self::$ROUTES[ 1 ]) as $part ){
                    $class_name .= ucfirst($part);
                }
                
                if ( file_exists( APP_DIR . '/classes/scripts/' . $class_name . '.php' ) )
                {
                    require_once APP_DIR . '/classes/scripts/' . $class_name . '.php';
                    
                    if( method_exists( $class_name, 'do' ) ) {

                        $class_name::do();
                    }
                }
            }
            
            // do.. funcitons
            if ( count( self::$ROUTES ) >= 2 && preg_match( '/^do/um', self::$ROUTES[0] ) ) {

                $class_name = ucfirst( substr( self::$ROUTES[ 0 ], 2 ) );
                if ( class_exists( $class_name ) && method_exists( $class_name, self::$ROUTES[ 1 ] ) ) {

                    $tmp_class = new $class_name;
                    $tmp_class::{self::$ROUTES[ 1 ]}();
                }
            }

            // basic tenant routing .. 
            if ( isset( self::$ROUTES[ 0 ] ) ) {
                self::$TEENANT = self::$ROUTES[ 0 ];
            }

            if ( isset( $tmp[ 1 ] ) ) {
                self::$URL_METHOD = self::$ROUTES[ 1 ];
            }
        }
    }

}
