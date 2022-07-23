<?php

    // app definition and init..
    define( 'WWW_DIR', __DIR__ );
    define( 'APP_DIR', __DIR__ . '/app' );

    session_start();
    
    // getting backbone of the app
    require_once APP_DIR . '/app.php';

    // here happens all magic
    $app = new App();

    // shortcut to language translation
    function _l( $text )
    {
        return Lang::l( $text );
    }
    
    // let's go..
    Template::layout();
?>