<?php

/** Hey, hey you! Call me: /script/db-update */

if( !defined('APP_VERSION') ){
    exit();
}

class DbUpdate {

    const TABLE_NAME = 'migration';
    const LOG_NAME = 'db_migration';
    const ALLOWED_FILE_EXTENSIONS = [ 'sql' ];

    /**
     * Main function, nothing much to say here
     */
    public static function do() {

        if( APP_DEBUG ){
            Log::flush( self::LOG_NAME );
        }
        
        self::log( 'Migration script started' );

        self::verifyMigrationTable();

        self::executeFiles();

        Settings::set( 'APP_VERSION' , APP_VERSION );
                
        self::log( 'Migration script finished' );

        // send user back if db update has been forced
        if( isset($_GET['forced']) ){
            
            // getting referer
            $referer = isset( $_SERVER[ 'HTTP_REFERER' ] ) && strlen( $_SERVER[ 'HTTP_REFERER' ] ) ? $_SERVER[ 'HTTP_REFERER' ] : '/';
    
            Helper::redirect( $referer );
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
        Log::store( self::LOG_NAME, $message );
    }

    /**
     * Check if migration table for storing all migrations 
     * already exists. If does not, create the new one
     */
    private static function verifyMigrationTable() {

        self::log( 'Checking migration table' );

        if ( !Core::tableExists( self::TABLE_NAME ) ) {

            self::log( 'Migration table does not exists, creating the new one' );

            $sql = "
                CREATE TABLE `migration` (
                    `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    `name` varchar(255) COLLATE 'utf8_general_ci' NOT NULL,
                    `created` datetime NOT NULL
                ) COLLATE 'utf8_general_ci' ";

            App::$DB->query( $sql );

            self::log( 'Migration table created' );
        } else {
            self::log( 'Migration table already exists' );
        }
    }

    /**
     * Go through all SQL files and install the new ones
     */
    private static function executeFiles() {

        foreach ( Helper::scan_dir( APP_DIR . '/sql/migration', self::ALLOWED_FILE_EXTENSIONS, true ) as $sql_file ) {

            // getting name of file
            $file_name = Helper::get_file_from_path( $sql_file );

            self::log( 'Checking migration file ' . $file_name );

            // check if sql file is installed
            $sql = '
                SELECT 
                    * 
                FROM ' . self::TABLE_NAME . ' 
                WHERE 
                    name = %s';

            $migration_data = App::$DB->query( $sql, $file_name )->fetch();

            if ( !$migration_data ) {

                // do the magic..

                self::log( 'Installing new scripts' );

                $sql = file_get_contents( $sql_file );

                App::$DB->query( $sql );

                Core::insert( [ 'name' => $file_name ], self::TABLE_NAME );

                self::log( 'Installation success' );
            } else {

                self::log( 'Migration already installed' );
            }
        }
    }

}
