<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Maintenance mode</title>

        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1" />

        <link rel="apple-touch-icon" sizes="180x180" href="//<?= $_SERVER['HTTP_HOST'] ?>/resources/img/favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="//<?= $_SERVER['HTTP_HOST'] ?>/resources/img/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="//<?= $_SERVER['HTTP_HOST'] ?>/resources/img/favicon/favicon-16x16.png">
        <link rel="manifest" href="//<?= $_SERVER['HTTP_HOST'] ?>/resources/img/favicon/site.webmanifest">
        <link rel="mask-icon" href="//<?= $_SERVER['HTTP_HOST'] ?>/resources/img/favicon/safari-pinned-tab.svg" color="#4a8af5">
        <meta name="msapplication-TileColor" content="#4a8af5">
        <meta name="theme-color" content="//<?= $_SERVER['HTTP_HOST'] ?>/resources/img/favicon#4a8af5">

        <link rel="stylesheet" href="//<?= $_SERVER['HTTP_HOST'] ?>/resources/css/bootstrap.min.css">
        <link rel="stylesheet" href="//<?= $_SERVER['HTTP_HOST'] ?>/resources/css/main.css">
    </head>
    <body>

        <div class="main-container">
            
            <h1>
                <div class="logo"></div>
                Maintenance mode
            </h1>
            
            <p>&nbsp;</p>
            
            <p class="big">
                Application is currently in maintenance mode.
                <br class="hidden-xs" />
                Please try again in few minutes.
            </p>
            
            <p>&nbsp;</p>
            
            <div class="copyright">
                &copy;<?= date( 'Y' ) ?> Ivan Mišák
            </div>
            
        </div>
        
    </body>
</html>