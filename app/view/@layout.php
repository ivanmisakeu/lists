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

        </div>

        <?= Template::include( 'footer' ) ?>
    </body>
</html>