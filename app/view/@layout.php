<!DOCTYPE html>
<html lang="<?= Lang::getLang(); ?>">
    <head>
        <?= Template::include( 'head' ) ?>
    </head>
    <body>
        <?= Template::include( 'header' ) ?>
        
        <div class="main-container">

            <?php if ( App::$TEENANT && Tenant::getByName( App::$TEENANT ) ): ?>

                <?php Items::actionIndex(); ?>

            <?php else: ?>

                <?= Tenant::actionIndex(); ?>

            <?php endif; ?>

            <hr />
            
            <div class="copyright">
                &copy;<?= date('Y') ?> Ivan Mišák | <?= _l('Version') . ' ' . APP_VERSION ?> 
            </div>
            
        </div>

        <?= Template::include( 'footer' ) ?>
    </body>
</html>