<?php

if (!defined( 'APP_VERSION' )) {
    exit();
}

class Admin{
    
    /** @var string */
    public static $HTML_CONTENT;
    
    /** @const array */
    public static $MENU = array(); 
    
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
    
    
    /* ------- ** OTHER FUNCTIONS ** ------- */
    
    public static function menuInit(){
        
        self::$MENU = array(
            'tenant' => [ Lang::l('Lists') , 'fa-list-ul'],
            'user' => [ Lang::l('Users') , 'fa-user-o' ],
            'tools' => [ Lang::l('Tools') , 'fa-wrench']
        );
    }
}