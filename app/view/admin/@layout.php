<!DOCTYPE html>
<html lang="<?= Lang::getLang(); ?>">
    <head>
        <?= Template::include( 'head' ) ?>
    </head>
    <body>
        
        <?php if( !Template::$FULL_VIEW ): ?>
        <div class="main-container admin-container">
            <?= Template::include( 'header' ) ?>
        <?php endif; ?>

            <?php Admin::renderHTMLContent(); ?>
            
        <?php if( !Template::$FULL_VIEW ): ?>
        </div>
        <?php endif; ?>

        <?= Template::include( 'footer' ) ?>
    </body>
</html>