<?php

/** Hey, hey you! Call me: /script/db-backup */

if( !defined('APP_VERSION') ){
    exit();
}

define( 'DB_BACKUP_DIR' , APP_DIR . '/sql/backup' );

class DbBackup {

    /** @var Trim SQL dump by XY rows */
    const ROWS_LIMIT = 50;
    
    /** @var array */
    private static $TABLES_LIST = array();
    
    /** @var array */
    private static $EXTRACTED_DATA = array();
    
    /**
     * Main function, nothing much to say here
     */
    public static function do() {

        if( APP_DEBUG ){
            Log::flush( Log::TYPE_DB_BACKUP );
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
        
        self::log( 'Backup script started' );

        self::getTables();
        
        // getting all table datas..
        self::$EXTRACTED_DATA = array();
        
        foreach( self::$TABLES_LIST as $table_name ){
         
            self::$EXTRACTED_DATA[] = "\n-- Dump for table '" . $table_name . "'\n\n";
            self::$EXTRACTED_DATA[] = self::getCreateTableCommand( $table_name ) . "\n\n";

            $table_column_names = self::getTableColumnNames( $table_name );
            $table_count = self::getDataCount( $table_name );

            self::generateSqlInsert( $table_name , $table_column_names , $table_count );
            
        }
        
        self::saveToFile();
        
        Settings::set( 'DB_BACKUP' , Core::now() );
                
        self::log( 'Backup script finished' );
        
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
        Log::store( Log::TYPE_DB_BACKUP, $message );
    }
    
    private static function saveToFile(){
        
        if( !file_exists( DB_BACKUP_DIR ) ){
            mkdir( DB_BACKUP_DIR );
        }
        
        $backup_file_name = DB_NAME . '_' . (new \DateTime)->format('Y-m-d') . '.sql';
        
        // some metadata to look like professional SQL dump
        $meta_data  = "\n-- Database backup of List app by Ivan Misak (info@ivanmisak.eu)";
        $meta_data .= "\n-- Created: " . (new \DateTime)->format('d.m.Y H:i:s');
        $meta_data .= "\n-- Dump script: /scripts/DbBackup.php";
        $meta_data .= "\n-- MySQL version: " . self::getMySQLVersion();
        $meta_data .= "\n-- Database name: " . DB_NAME . "\n";
     
        file_put_contents( DB_BACKUP_DIR . '/' . $backup_file_name , $meta_data . join('',self::$EXTRACTED_DATA) );
        
        self::log( 'File ' . $backup_file_name . ' generated' );
    }

    /**
     * Get current MySQL database version
     * 
     * @return string
     */
    private static function getMySQLVersion(){
        
        $sql = 'SELECT @@VERSION AS "version"';
        
        return App::$DB->query( $sql )->fetchSingle();
    }
    /**
     * Get list of all tables
     */
    private static function getTables(){
        
        self::log( 'Getting list of tables' );
        
        self::$TABLES_LIST = array();
        
        $sql = 'SHOW TABLES;';
        $result = App::$DB->query( $sql )->fetchAll();
        
        foreach( $result as $row ){
            self::$TABLES_LIST[] = $row['Tables_in_' . DB_NAME];
        }
        
        self::log( 'Found ' . count(self::$TABLES_LIST) . ' tables' );
       
    }
    
    /**
     * Get all column names from table 
     * 
     * @param string $table_name
     * @return array
     */
    private static function getTableColumnNames( string $table_name ){
        
        self::log( 'Getting list of columns in table \'' . $table_name ) . '\'';
        
        $table_column_names = array();
        
        $sql = "
            SELECT 
                COLUMN_NAME 
            FROM INFORMATION_SCHEMA.COLUMNS 
            WHERE 
                TABLE_NAME = N'" . $table_name . "' 
                AND TABLE_SCHEMA='" . DB_NAME . "'";
        
        $result = App::$DB->query( $sql )->fetchAll();

        foreach( $result as $row ){
            $table_column_names[] = $row['COLUMN_NAME'];
        }
        
        self::log( 'Found ' . count($table_column_names) . ' columns: ' . join(', ',$table_column_names) );
        
        return $table_column_names;
    }
    
    /**
     * Get SQL command for table creation
     * 
     * @param string $table_name
     * @return string
     */
    private static function getCreateTableCommand( string $table_name ){
        
        $sql = 'SHOW CREATE TABLE ' . $table_name;
        
        $result = APP::$DB->query( $sql )->fetch();
        $result = isset( $result['Create Table'] ) ? $result['Create Table'] : null;
        $result = str_replace('ENGINE=InnoDB ','', $result);
        $result = str_replace(' DEFAULT CHARSET=utf8','', $result);
        
        return $result;
    }
    
    /**
     * Get count of all rows in table
     * 
     * @param string $table_name
     * @return int
     */
    private static function getDataCount( string $table_name ){
        
        $sql = 'SELECT COUNT(*) AS "cnt" FROM ' . $table_name;
        
        return (int) App::$DB->query( $sql )->fetchSingle();
    }
    
    /**
     * Create table insert for data 
     * 
     * @param string $table_name
     * @param array $table_column_names
     * @param int $table_data_count
     * @return void
     */
    private static function generateSqlInsert( string $table_name , array $table_column_names, int $table_data_count ){
     
       
        if( !count($table_column_names) ){
            return;
        }
        
        self::log( 'Generating dump' );
        
        // create insert part of script
        // adding stupid SQL quotes to column names..
        $table_column_names_for_insert = $table_column_names;
        foreach( $table_column_names_for_insert as &$col ){
            $col = Helper::str_add_sql_quotes( $col );
        }
        $sql_insert = "INSERT INTO `" . $table_name . "` (" . join(', ' , $table_column_names_for_insert) . ") VALUES ";
        
        // main logic
        $offset = 0;
        while( $offset < $table_data_count ){
            
            $sql = "SELECT " . join(', ' , $table_column_names) . " 
                    FROM " . $table_name . " 
                    LIMIT " . self::ROWS_LIMIT . " 
                    OFFSET " . $offset;
            
            $rows = App::$DB->query( $sql )->fetchAll();
            
            $sql_command = $sql_insert;
            
            foreach( $rows as $key => &$row ){
                
                $row = (array) $row;
                
                foreach( $table_column_names as $column_name ){
                    
                    if( $row[$column_name] instanceof Dibi\DateTime ){
                        $row[$column_name] = (new DateTime( $row[$column_name] ))->format('Y-m-d H:i:s');
                    }
                    
                    if( strlen($row[$column_name]) ){
                        $row[$column_name] = "'" . $row[$column_name] . "'";
                    }
                    else{
                        $row[$column_name] = 'NULL';
                    }
                }
                
                $sql_command .= ( $key > 0 ? ',' : '') . "\n(" . join(', ', $row) . ")";
            }
            
            $sql_command .= ";\n";
            
            // save result to array
            self::$EXTRACTED_DATA[] = $sql_command;
            
            // increment offset ofcourse
            $offset += self::ROWS_LIMIT;
            
        };
        
        self::log( 'Dump successfully created' );
        
    }
      
}
