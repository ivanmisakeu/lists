<!DOCTYPE html>
<html lang="<?= Lang::getLang(); ?>">
    <head>
        <title><?= _l( 'Shopping list' ) ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1" />

        <link rel="stylesheet" href="/resources/css/bootstrap.min.css">
        <link rel="stylesheet" href="/resources/css/font-awesome.css">
        <link rel="stylesheet" href="/resources/css/main.css<?= Helper::res_timestamp( 'css/main.css' ); ?>">
    </head>
    <body>

        <div class="main-container">

            <?php if ( App::$TEENANT && Tenant::getByName( App::$TEENANT ) ): ?>

                <?php Template::render( 'item' ); ?>

            <?php else: ?>

                <?php Template::render( 'tenant' ); ?>

            <?php endif; ?>

        </div>

        <?php Helper::flash_show(); ?>

        <script src="/resources/js/jquery-2.2.4.min.js"></script>
        <script src="/resources/js/main.js<?= Helper::res_timestamp( 'js/main.js' ); ?>"></script>
        <script>
            _app.lang.translations = {
                'Please fill all required fields': '<?= _l( 'Please fill all required fields' ) ?>',
            };
        </script>
    </body>
</html>