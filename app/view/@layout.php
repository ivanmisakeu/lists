<!DOCTYPE html>
<html lang="<?= Lang::getLang(); ?>">
    <head>
        <?= Template::include( 'head' ) ?>
    </head>
    <body>
        <?= Template::include( 'header' ) ?>
        
        <?php if( !Template::$FULL_VIEW ): ?><div class="main-container"><?php endif; ?>

            <?php if ( App::$TEENANT && Tenant::getByName( App::$TEENANT ) ): ?>

                <?php Items::actionIndex(); ?>

            <?php else: ?>

                <?= Tenant::actionIndex(); ?>

            <?php endif; ?>

            <hr />
            
            <div class="copyright">
                &copy;<?= date('Y') ?> Ivan Mišák | <?= _l('Version') . ' ' . APP_VERSION ?> 
            </div>
            
        <?php if( !Template::$FULL_VIEW ): ?></div><?php endif; ?>

        <?= Template::include( 'footer' ) ?>
    </body>
</html>