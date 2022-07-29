<?php

/** Hey, hey you! Call me: /script/find-missing-translations */

if ( !defined( 'APP_VERSION' ) ) {
    exit();
}

class FindMissingTranslations {

    /** @var array */
    const EXPLODE_OPTIONS = array(
        ["Lang::l('","')"],
        ["Lang::l( '","' )"],
        ["Lang::l(\"","\")"],
        ["Lang::l( \"","\" )"],
        ["_l('","')"],
        ["_l( '","' )"],
        ["_l(\"","\")"],
        ["_l( \"","\" )"]
    );  
    
    /** @var array */
    private static $system_translations = array();
    
    /** @var array */
    private static $languages_found = array();

    /**
     * Main function, nothing much to say here
     */
    public static function do() {

        if( APP_DEBUG ){
            Log::flush( Log::TYPE_MISSING_TRANSLATIONS );
        }
        
        // start HTML
        if( isset($_GET['format_result']) ){
            
            echo '
                <link rel="stylesheet" href="' . APP_URL . '/resources/css/bootstrap.min.css">
                <link rel="stylesheet" href="' . APP_URL . '/resources/css/font-awesome.css">
                <style>
                    .main-content{ padding: 15px;
                </style>

                <div class="main-content">';
        }
        
        self::log( 'Script started' );

        self::searchForTranslationFiles();

        self::searchForTranslationSources();

        foreach ( self::$languages_found as $current_lang ) {

            echo '<hr />';
            self::log( 'Generating file for ' . strtoupper( $current_lang ) . ':' );

            self::generateFile( $current_lang );
        }

        self::log( 'Script finished' );

        // end HTML
        if( isset($_GET['return_url']) && isset($_GET['format_result']) ){
            
            echo '
                <br />
                <a href="' . $_GET['return_url'] . '" class="btn btn-primary">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> ' . _l('Go back') . '
                </a>';
        }
        
        if( isset($_GET['format_result']) ){
            echo '</div>';
        }
        
        exit();
    }

    /**
     * We need to know of course..
     * 
     * @param string $message
     */
    private static function log( string $message ) {

        echo $message . '<br />';
        Log::store( Log::TYPE_MISSING_TRANSLATIONS, $message );
    }

    /**
     * Search already available translations on server
     */
    private static function searchForTranslationFiles() {

        self::$languages_found = array();

        foreach ( Helper::scan_dir( TRANSLATIONS_DIR, [ 'php' ] ) as $file_name ) {

            self::$languages_found[] = strtolower( str_replace( '.php', '', Helper::get_file_from_path( $file_name ) ) );
        }

        self::log(
                'Found ' . count( self:: $languages_found ) . ' translation file' .
                ( count( self:: $languages_found ) == 1 ? '' : 's') .
                ( count( self:: $languages_found ) ? (' (' . strtoupper( join( ', ', self:: $languages_found ) ) . ')') : '' )
        );
    }

    private static function searchForTranslationSources() {

        $ignore_paths_while_scanning = [
            WWW_DIR . '/resources',
            APP_DIR . '/sql',
            APP_DIR . '/logs',
            APP_DIR . '/config',
            APP_DIR . '/classes/scripts'
        ];

        $php_files = Helper::scan_dir(
                        WWW_DIR,
                        [ 'php' ],
                        true,
                        [ '.', '..', '.DS_Store', '__a.php' ],
                        array_merge( Helper::SCAN_DIR_EXCLUDED_PATHS, $ignore_paths_while_scanning )
        );

        self::log( 'Found ' . count( $php_files ) . ' source files to search in..' );

        foreach ( $php_files as $php_file ) {

            self::log( 'Investigating file: ' . str_replace( WWW_DIR, '', $php_file ) );

            self::saerchInTranslationSource( $php_file );
        }

        self::log( 'Found ' . count( self::$system_translations ) . ' translations in source code' );
    }

    private static function saerchInTranslationSource( string $file_path ) {

        $file_content = file_get_contents( $file_path );
        $file_content = str_replace( '<?php', '', $file_content );

        foreach( self::EXPLODE_OPTIONS as $option){
            
            foreach ( explode( $option[0], $file_content ) as $key => $tmp ) {

                if ( $key == 0 ) {
                    continue;
                }

                $tmp = explode( $option[1], $tmp );

                if ( !in_array( $tmp[ 0 ], self::$system_translations ) ) {

                    self::$system_translations[] = $tmp[ 0 ];
                }
            }
        }
    }

    private static function generateFile( string $current_lang ) {

        $found = array();
        $missing = array();

        Lang::loadLang( $current_lang );
        $lang_array = Lang::getLangArray();

        self::log( "Translation file contains " . count( $lang_array ) . " translations" );

        foreach ( self::$system_translations as $system_translation ) {

            if ( array_key_exists( $system_translation, $lang_array ) &&
                    !preg_match( '/^#LANG#\\:/usm', $lang_array[ $system_translation ] )
            ) {

                $found[] = $system_translation;
            } else {

                $missing[] = $system_translation;
            }
        }

        // generating file content -- start
        $text = "<?php\n\nLang::$" . "translation_array = [\n";

        foreach ( $found as $namespace ) {

            $text .= "\t'" . $namespace . "' => '" . $lang_array[ $namespace ] . "',\n";
        }

        if ( count( $missing ) ) {

            $text .= "\n\t/** MISSING TRANSLATIONS **/\n\n";
        }

        foreach ( $missing as $namespace ) {

            $text .= "\t'" . $namespace . "' => '#LANG#:" . $namespace . "',\n";
        }

        $text .= "];";
        // generating file content -- end

        if ( count( $missing ) ) {

            self::log( 'Found ' . count( $missing ) . ' missing translation' . (count( $missing ) == 1 ? '' : 's') );
        } else {

            self::log( 'Everything is up to date' );

            return;
        }

        file_put_contents( TRANSLATIONS_DIR . '/' . strtolower( $current_lang ) . '.php', $text );

        self::log( 'Translation file succesfully generated.' );
    }

}
