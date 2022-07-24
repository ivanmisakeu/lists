<?php

if( !defined('APP_VERSION') ){
    exit();
}

class Template{
    
    /* @const string */
    const TEMPLATE_DIR = APP_DIR . '/view';
    
    /* @const string */
    const TEMPLATE_DIR_ADMIN = APP_DIR . '/view/admin';
    
    /** @var string */
    public static $HTML_TITLE;
    
    /** @var string */
    private static $TEMPLATE_LAYOUT_FILE = '@layout';
    
    /** @var StdObject */
    private static $VARIABLES;
    
    /**
     * Assign variable to template
     * 
     * @param string $namespace
     * @param mixed $data
     */
    public static function assign( string $namespace , $data ){

        if( self::$VARIABLES == null ){
            
            self::$VARIABLES = new StdObject();
        }
        
        self::$VARIABLES->set( $namespace , $data );
    }
    
    /**
     * Get template dir in order if user is on admin or in front
     * @return string
     */
    public static function getTemplateDir(){
        
        if( defined('ADMIN_DIR') ){
            
            return self::TEMPLATE_DIR_ADMIN;
        }
        
        return self::TEMPLATE_DIR;
    }
    
    /**
     * Render layout for application
     */
    public static function layout(){
        
        self::render( self::$TEMPLATE_LAYOUT_FILE );
    }
    
    /**
     * Sets custom layout file
     * 
     * @param string $path
     * @throws Exception
     */
    public static function setLayout( string $path ){
        
        if( !file_exists( self::getTemplateDir() . '/' . $path . '.php' ) ){
            
            throw new Exception('Layout with path "' . $path . '" does not exists');
        }
        
        self::$TEMPLATE_LAYOUT_FILE = $path;
    }
    
    /**
     * Include page parts
     * 
     * @param string $path
     */
    public static function include( string $path ){
        
        echo self::fetch( '@includes/' . $path ); 
    }
    
    /**
     * Render single template
     * 
     * @param string $path
     */
    public static function render( string $path ){
        
        echo self::fetch( $path ); 
    }
    
    /**
    * Return single template as HTML
    * 
    * @param string $path
    * @return string
    * @throws Exception
    */
    public static function fetch( string $path ){

        // load assigned variables
        if( self::$VARIABLES != null ){
            
            foreach( self::$VARIABLES->keys() as $name ){
                ${$name} = self::$VARIABLES->{$name};
            }
            
            self::$VARIABLES = new StdObject();
        }

        // load template files
        if( file_exists( self::getTemplateDir() . '/' . $path . '.php' ) ){
            
            ob_start();
            include self::getTemplateDir() . '/' . $path . '.php';
            $html = ob_get_contents();
            ob_end_clean();
            
            return $html;
        }
        else if( file_exists( self::getTemplateDir() . '/' . $path . '/index.php' ) ){
            
            ob_start();
            include self::getTemplateDir() . '/' . $path . '/index.php';
            $html = ob_get_contents();
            ob_end_clean();
            
            return $html;
        }
        
        throw new Exception('Template with path "' . $path . '" does not exists');
    }
    
    /**
     * Page title will be rewriten via JS
     * 
     * @param string $title
     */
    public static function setPageTitle( string $title ){

        self::$HTML_TITLE = $title;
    }

}