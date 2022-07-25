<?php

if ( !defined( 'APP_VERSION' ) ) {
    exit();
}

class Helper {

    /* ------- ** DEBUG FUNCTIONS ** ------- */

    /**
     * Dumps single variable
     * 
     * @param mixed $val
     */
    public static function d( $val ) {

        print '<pre>' . print_r( $val, TRUE ) . '</pre>';
    }

    /**
     * Dumps single variable and stop script execution
     * 
     * @param mixed $val
     */
    public static function dd( $val ) {

        self::d( $val );
        die;
    }

    /* ------- ** URL ADDRESS FUNCTIONS ** ------- */

    /**
     * Redirects user to given URL address
     * 
     * @param string $url
     */
    public static function redirect( string $url ) {

        // close session .. because: fuck off!
        session_write_close();
        session_regenerate_id( true );

        header( 'Location: ' . $url );
        die;
    }
    
    /**
     * Redirect user to url posted in 'redirect_url' field in form
     */
    public static function redirect_to_posted_url(){
        
        $redirect_url = (defined( 'ADMIN_URL' ) ? ADMIN_URL : APP_URL) . '/';
                
        if( isset($_POST['redirect_url']) ){
            $redirect_url = $_POST['redirect_url'];
        }
        
        self::redirect( $redirect_url );
    }
    
    /**
     * Redirects user home with error message
     */
    public static function redirect_error_home(){
        
        Helper::flash_set( Lang::l( 'Whoops, something went wrong!' ), Helper::FLASH_DANGER );
        Helper::redirect( (defined('ADMIN_URL') ? ADMIN_URL : APP_URL) . '/' );
    }

    /* ------- ** FLASH MESSAGES ** ------- */
    const FLASH_PRIMARY = 'primary';
    const FLASH_SECONDARY = 'secondary';
    const FLASH_SUCCESS = 'success';
    const FLASH_WARNING = 'warning';
    const FLASH_DANGER = 'danger';
    const FLASH_INFO = 'info';
    const FLASH_ALL = [ 'primary', 'secondary', 'success', 'warning', 'danger', 'info' ];

    /**
     * Sets flash message to session
     * 
     * @param string $text
     * @param string $type (opt.) - bootstrap classes like 'success', 'warning', 'danger' etc.
     */
    public static function flash_set( string $text, string $type = self::FLASH_SUCCESS ) {

        if ( !in_array( $type, self::FLASH_ALL ) ) {
            return;
        }

        $_SESSION[ "flash_message" ] = [
            'message' => $text,
            'type' => $type
        ];
    }

    /**
     * Get flash message from session
     * 
     * @return object
     */
    public static function flash_get() {

        $result = null;

        if ( isset( $_SESSION[ "flash_message" ] ) && is_array( $_SESSION[ "flash_message" ] ) ) {
            $result = new StdObject( $_SESSION[ "flash_message" ] );
            unset( $_SESSION[ "flash_message" ] );
        }

        return $result;
    }

    /**
     * Show flash message (if exists) as HTML, yeah!
     */
    public static function flash_show() {

        $flash = Helper::flash_get();
        if ( $flash ) {
            echo '<div id="flash-message" class="alert alert-' . $flash->type . '">' . $flash->message . '</div>';
        }
    }

    /* ------- ** FORM CAPTCHA ** ------- */

    /**
     * Generate captcha hidden input
     * 
     * @return string
     */
    public static function captcha_get() {

        return '<input type="hidden" name="__cc' . rand( pow( 10, 15 ), pow( 10, 16 ) ) . '" />';
    }

    /**
     * Process captcha value
     */
    public static function captcha_verify() {

        // getting referer
        $referer = isset( $_SERVER[ 'HTTP_REFERER' ] ) && strlen( $_SERVER[ 'HTTP_REFERER' ] ) ? $_SERVER[ 'HTTP_REFERER' ] : '/';

        // do the magic..
        foreach ( $_POST as $key => $value ) {

            if ( preg_match( '/^__cc[0-9]{1,}/usm', $key ) ) {

                $key = str_split( substr( $key, 4 ) );
                sort( $key );
                if ( join( '', $key ) != $value ) {

                    Helper::flash_set( Lang::l('Captcha validation failed'), Helper::FLASH_DANGER );
                    Helper::redirect( $referer );
                }
            }
        }
    }

    /* ------- ** STRING FUNCTIONS ** ------- */

    /**
     * Remove special characters from string
     * leaves only A-Z a-z 0-9 -
     * 
     * @param string $string
     * @return string
     */
    public static function str_clean( string $string ) {

        $string = str_replace( ' ', '-', $string );
        return preg_replace( '/[^A-Za-z0-9\-]/', '', $string );
    }

    /**
     * Clears string to be used as tenant name
     * 
     * @param string $string
     * @return string
     */
    public static function str_clear_tenant_name( string $string ) {

        $string = str_replace( '.', '-', $string );
        $string = strtolower( $string );

        return self::str_clean( $string );
    }
    
    /**
     * Explode string with commas into array items and trims content text
     * 
     * @param string $string
     * @return array
     */
    public static function str_explode_commas( string $string ){
        
        // check if there are numbers with comma as decimal point
        preg_match_all('/\\d,\\d/usm', $string , $matches , PREG_OFFSET_CAPTURE );
        if( isset( $matches[0]) && count($matches[0]) ){
            
            foreach($matches[0] as $match){
                
                $string = str_replace( $match[0] , $match[0][0] . '##DECIMAL_POINT##' . $match[0][2], $string );
            }
        }
        $string = preg_replace('/\\d,\\d/usm', '\\d##DECIMAL_SYMBOL##\\d', $string);;
   
        // explode input into rows
        $rows = explode(',',$string);
        foreach( $rows as &$row ){
            $row = trim( str_replace('##DECIMAL_POINT##',',',$row));
        }
        
        return $rows;
    }
    
    /**
     * Escape some special HTML characters
     * 
     * @param string $string
     * @return string
     */
    public static function str_remove_html( string $string ){
        
        $string = str_replace('&', '&amp;', $string);
        $string = str_replace('<', '&lt;', $string);
        $string = str_replace('>', '&gt;', $string);
        $string = str_replace('"', '&quot;', $string);
        $string = str_replace("'", '&#39;', $string);
        
        return $string;
    }
    
    /**
     * Returns nickname from email without @ part
     * 
     * @param string $email
     * @return string
     */
    public static function str_nick_from_email( string $email ){
        
        return explode('@', $email)[0];
    }
    
    /**
     * Add special SQL quotes to name of table / column
     * 
     * @param string $string
     * @return string
     */
    public static function str_add_sql_quotes( string $string ){
        
        return '`' . $string . '`';
    }
    
    /**
     * Removes mobile automatic first letter uppercase
     * 
     * @param string $string
     * @return string
     */
    public static function str_mobile_first_letter( string $string ){
        
        if( lcfirst( $string ) == strtolower( $string ) ){
            return lcfirst( $string );
        }
        
        return $string;
    }
    
    /**
     * Hash user password
     * 
     * @param string $password
     * @return string
     */
    public static function str_hash_password( string $password ){
        
        return md5( APP_SALT . $password );
    }

    /* ------- ** FILE WORK FUNCTIONS ** ------- */

    /**
     * Get modification time of resources/assets as GET parameter
     * 
     * @param string $file_path
     * @return string
     */
    public static function res_timestamp( string $file_path ) {

        $file_path = WWW_DIR . '/resources/' . $file_path;

        if ( file_exists( $file_path ) ) {
            return '?t=' . filemtime( $file_path );
        }

        return null;
    }

    /**
     * Gets extension of file
     * 
     * @param string $file_name
     * @return string
     */
    public static function get_file_extension( string $file_name ) {

        $tmp = explode( '.', $file_name );
        return strtolower( $tmp[ count( $tmp ) - 1 ] );
    }

    /**
     * Gets file name from path
     * 
     * @param string $file_path
     * @return string
     */
    public static function get_file_from_path( string $file_path ) {

        $tmp = explode( '/', $file_path );
        return $tmp[ count( $tmp ) - 1 ];
    }

    const SCAN_DIR_IGNORED_FILES = [ '.', '..', '.DS_Store', '__a.php', 'index.php' ];
    const SCAN_DIR_EXCLUDED_PATHS = [ APP_DIR . '/libraries' ];

    /**
     * Scan dir recursively and find all files matching file allowed extensions 
     * 
     * @param string $dir_path
     * @param array $allowed_extensions
     * @param bool $recursively
     * @param array $ignored_files
     * @param array $excluded_paths
     * @return array
     */
    public static function scan_dir(
            string $dir_path,
            array $allowed_extensions = array(),
            $recursively = false,
            array $ignored_files = self::SCAN_DIR_IGNORED_FILES,
            array $excluded_paths = self::SCAN_DIR_EXCLUDED_PATHS
    ) {
        
        $dir_content = array();

        // nothing to see here..
        if ( !file_exists( $dir_path ) ) {

            return $dir_content;
        }

        // scan dir
        foreach ( scandir( $dir_path ) as $file ) {

            if ( in_array( $file, $ignored_files ) ) {
                continue;
            }

            
            if ( is_dir( $dir_path . '/' . $file ) &&
                    $recursively && !in_array( $file, $dir_content ) &&
                    !in_array( $dir_path . '/' . $file, $excluded_paths )
            ) {

                $dir_content = array_merge($dir_content, self::scan_dir( $dir_path . '/' . $file, $allowed_extensions, $recursively, $ignored_files , $excluded_paths ) );
            } else if ( !count( $allowed_extensions ) ||
                    in_array( Helper::get_file_extension( $file ), $allowed_extensions ) ) {

                $dir_content[] = $dir_path . '/' . $file;
            }
        }

        return $dir_content;
    }

    /* ------- ** VALIDATION FUNCTIONS ** ------- */
    
    /**
     * Validation of email address
     * 
     * @param string $email
     * @return boolean
     */
    public static function validate_email( string $mail ){
        
        if (!filter_var($mail, FILTER_VALIDATE_EMAIL) ) {
            
            return false;
        }   
        
        return true;
    }
}
