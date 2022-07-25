<?php

if (!defined( 'APP_VERSION' )) {
    exit();
}

class Admin{
    
    public static $HTML_CONTENT;
    
    /* ------- ** TEMPLATE FUNCTIONS ** ------- */
    
    public static function actionIndex_admin(){

        self::$HTML_CONTENT = 'OU YEAH!';
    }
    
    /* ------- ** SYSTEM FUNCTIONS ** ------- */
    
    /** 
     * Render content for page 
     */
    public static function renderHTMLContent(){
        
        echo self::$HTML_CONTENT;
    }
    
    /* ------- ** ACTION FUNCTIONS ** ------- */
    
}