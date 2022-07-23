<?php

if ( !defined( 'APP_VERSION' ) ) {
    exit();
}

define( 'TRANSLATIONS_DIR' , APP_DIR . '/translation' );

class Lang {

    const LANG_DEFAULT = 'en';
    
    /** @var array */
    private static $translation_array = array();

    /**
     * Init function for loading current language
     */
    public static function init() {

        if ( defined( 'APP_LANG' ) &&
                file_exists( TRANSLATIONS_DIR . '/' . strtolower( APP_LANG ) . '.php' ) ) {
            include TRANSLATIONS_DIR . '/' . strtolower( APP_LANG ) . '.php';
        }
    }

    /**
     * Translate single namespace in translation file
     */
    public static function l( string $text ) {

        if ( isset( self::$translation_array[ $text ] ) ) {
            return self::$translation_array[ $text ];
        }

        return $text;
    }
    
    /**
     * Load specified language file
     * 
     * @param string $lang_name
     */
    public static function loadLang( string $lang_name ){
        
        if ( file_exists( TRANSLATIONS_DIR . '/' . strtolower( $lang_name ) . '.php' ) ) {
            include TRANSLATIONS_DIR . '/' . strtolower( $lang_name ) . '.php';
        }
    }
    
    /**
     * Get current language
     * 
     * @return string
     */
    public static function getLang() {

        if ( defined( 'APP_LANG' ) &&
                file_exists( TRANSLATIONS_DIR . '/' . strtolower( APP_LANG ) . '.php' ) ) {

            return defined( 'APP_LANG' );
        }

        return self::LANG_DEFAULT;
    }
    
    /**
     * Return all language translations for current language
     * 
     * @return array
     */
    public static function getLangArray(){
        
        return self::$translation_array;
    }

}
