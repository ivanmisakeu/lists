<?php

if (!defined( 'APP_VERSION' )) {
    exit();
}

class Tools {
    
    /* ------- ** TEMPLATE FUNCTIONS ** ------- */
    
    public static function actionIndex_admin() {

        Template::generate_admin( 'tools/index' );
    }
    
}
