<?php

if (!defined( 'APP_VERSION' )) {
    exit();
}

class Admin{
    
    private static $HTML_CONTENT;
    
    public static function actionIndex_admin(){
        
        self::$HTML_CONTENT = 'OU YEAH!';
    }
    
    /** 
     * Render content for page 
     */
    public static function renderHTMLContent(){
        
        echo self::$HTML_CONTENT;
    }
}