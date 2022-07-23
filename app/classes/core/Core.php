<?php

if( !defined('APP_VERSION') ){
    exit();
}

class Core {

    const DB_DATE_FORMAT = 'Y-m-d H:i:s';

    public static function _get( int $id , string $TABLE_NAME ) {

        $sql = '
            SELECT 
                * 
            FROM ' . $TABLE_NAME . ' 
            WHERE 
                id = ' . (int) $id;
        
        return APP::$DB->query($sql)->fetch();
    }

    public static function insert( array $data , string $TABLE_NAME ) {

        $data = array_merge(
                $data,
                ['created' => self::now()]
        );
        
        APP::$DB->query( 'INSERT INTO ' . $TABLE_NAME . ' %v', $data );

        return APP::$DB->getInsertId();
    }
    
    /**
     * Checks if table with given name exists
     * 
     * @param string $TABLE_NAME
     * @return bool
     */
    public static function tableExists( string $TABLE_NAME ){
        
        $query_result = APP::$DB->query("SHOW TABLES LIKE %s;" , $TABLE_NAME)->fetchAll();
        
        return count( $query_result ) ? true : false;
    }
    
    /**
     * Return current timestamp in correct DateTime format
     * 
     * @return string
     */
    public static function now(){
        
        return (new DateTime )->format( self::DB_DATE_FORMAT );
    }

}
