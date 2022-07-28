<?php

if( !defined('APP_VERSION') ){
    exit();
}

class Settings extends Core {

    const TABLE_NAME = 'settings';
    
    /**
     * Sets settings variable
     * 
     * @param string $namespace
     * @param string $value
     */
    public static function set( string $namespace , string $value ){
        
        if( self::get( $namespace ) ) {
            
            App::$DB->query('UPDATE `' . self::TABLE_NAME . '` SET %a', [
                'value' => $value,
                'updated' => parent::now()
            ] , 'WHERE name = ?', self::clearNamespace($namespace));
        }
        else{
            
            parent::insert( [
                    'name' => self::clearNamespace($namespace),
                    'value' => $value,
                    'updated' => parent::now()
            ], self::TABLE_NAME );
        }
    }
    
    /**
     * Gets single setting value by it's namespace
     * 
     * @param string $namespace
     * @return string
     */
    public static function get( string $namespace ){

        $sql = '
            SELECT 
                value 
            FROM ' . self::TABLE_NAME . ' 
            WHERE 
                name = ?';
 
        return APP::$DB->query($sql,self::clearNamespace($namespace))->fetchSingle();
    }
    
    /**
     * Clears namespace from special characters etc.
     * 
     * @param string $namespace
     * @return string
     */
    public static function clearNamespace( string $namespace ){
        
        $namespace = str_replace( ' ', '-', $namespace );
        $namespace = strtoupper( $namespace );
        $namespace = preg_replace( '/[^A-Za-z0-9\-\.\_]/', '', $namespace );
        
        return $namespace;
    }

    /**
     * Get last db backup sql file
     * 
     * @return \StdObject
     */
    public static function getLastDbBackup(){
        
        $files = Helper::scan_dir( APP_DIR . '/sql/backup', ['sql'] );
        
        if( count($files) ){
            
            $file = end( $files );
            
            return new StdObject([
                'path' => $file,
                'link' => APP_URL . '/app/sql/backup/' . Helper::str_get_filename_form_path($file), 
                'name' => Helper::str_get_filename_form_path($file)
            ]);
        }
        
        return null;
    }
    
    /**
     * Get last SQL migration file done
     * 
     * @return array
     */
    public static function getLastMigrationFileDone(){
        
        $sql = '
                SELECT 
                    * 
                FROM migration 
                ORDER BY created DESC
                LIMIT 1';

        return APP::$DB->query( $sql )->fetch();
        
    }
    
    /**
     * Get last db backup sql file
     * 
     * @return \StdObject
     */
    public static function getLastDbMigrationFile(){
        
        $files = Helper::scan_dir( APP_DIR . '/sql/migration', ['sql'] );
        
        if( count($files) ){
            
            $file = end( $files );
            
            return new StdObject([
                'path' => $file,
                'link' => APP_URL . '/app/sql/migration/' . Helper::str_get_filename_form_path($file), 
                'name' => Helper::str_get_filename_form_path($file)
            ]);
        }
        
        return null;
    }
}
