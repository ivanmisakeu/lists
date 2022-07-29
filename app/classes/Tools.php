<?php

if (!defined( 'APP_VERSION' )) {
    exit();
}

class Tools {
    
    /* ------- ** TEMPLATE FUNCTIONS ** ------- */
    
    public static function actionIndex_admin() {

        Template::generate_admin( 'tools/index' );
    }
    
    public static function actionLog_admin() {

        if( !isset(Router::$ROUTES[2]) || !in_array( Router::$ROUTES[2], Log::TYPES_ALL ) ){
            
            Helper::flash_set( Lang::l( 'Selected type of logs does not exists' ), Helper::FLASH_DANGER );
            Helper::redirect( ADMIN_URL . '/tools' );
        }

        $log_files = Helper::scan_dir( APP_DIR . '/logs' , ['log'] );
        foreach( $log_files as $key => $file_path ){
            
            $file_name = Helper::str_get_filename_form_path( $file_path );
            if( preg_match('/^' . Router::$ROUTES[2] . '/um', $file_name) ){
                
                $log_files[$key] = $file_name;
            }
            else{
                
                unset( $log_files[$key] );
            }
        }
        
        Template::assign( 'type' , Router::$ROUTES[2]);
        Template::assign( 'log_files' , $log_files);
        Template::generate_admin( 'tools/log' );
    }
}
