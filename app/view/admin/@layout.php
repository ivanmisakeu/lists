<!DOCTYPE html>
<html lang="<?= Lang::getLang(); ?>">
    <head>
        <?= Template::include( 'head' ) ?>
    </head>
    <body>
        <?= Template::include( 'header' ) ?>
        
        <div class="main-container admin-container">

            <?php Admin::renderHTMLContent(); ?>
            
        </div>

        <?= Template::include( 'footer' ) ?>
    </body>
</html>