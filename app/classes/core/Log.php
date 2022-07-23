<?php

if( !defined('APP_VERSION') ){
    exit();
}

define( 'LOG_DIR' , APP_DIR . '/logs' );

class Log {
        
    const TYPE_DB_MIGRATION = 'db_migration';
    const TYPE_DB_BACKUP = 'db_backup';
    const TYPE_MISSING_TRANSLATIONS = 'missing-translations';
    const TYPES_ALL = [ self::TYPE_DB_MIGRATION , self::TYPE_DB_BACKUP, self::TYPE_MISSING_TRANSLATIONS ];
    
    /**
     * Store message into log file
     * 
     * @param string $name
     * @param type $data
     * @param bool $date
     */
    public static function store( string $name , $data , bool $date = true ){
        
        if( !file_exists( LOG_DIR ) ){
            mkdir( LOG_DIR );
        }
        
        $file = $name . ( $date ? ( '-' . (new \DateTime)->format('Y-m-d')) : '') . '.log';
        $data = '[' . (new \DateTime)->format('Y-m-d H:i:s') . '] ' . print_r( $data , true ) . "\n";
                
        if( file_exists(LOG_DIR . '/' . $file) ){
            
            $data = file_get_contents( LOG_DIR . '/' . $file ) . $data;
        }
        
        file_put_contents( LOG_DIR . '/' . $file , $data);
    }
    
    /** 
     * Removes log file
     * 
     * @param string $name
     * @param bool $date
     */
    public static function flush( string $name, bool $date = true ){
        
        $file = $name . ( $date ? ( '-' . (new \DateTime)->format('Y-m-d')) : '') . '.log';
        if( file_exists(LOG_DIR . '/' . $file) ){
            
            unlink( LOG_DIR . '/' . $file );
        }
    }

    
}