<!DOCTYPE html>
<html lang="<?= Lang::getLang(); ?>">
    <head>
        <?= Template::include( 'head' ) ?>
    </head>
    <body>
        
        <?php if( !Template::$FULL_VIEW ): ?>
        
        <?= Template::include( 'header' ) ?>
        
        <div class="main-container admin-container">
            
            <div class="admin-menu-wrapper">
                <ul>
                    <?php foreach( Admin::$MENU as $class_name => $row ): ?>
                    <li class="<?= Router::$ROUTES[0] == $class_name ? 'active' : '' ?>">
                        <a href="<?= ADMIN_URL . '/' . $class_name ?>" class="nowrap">
                            <?php if( isset($row[1]) ):?>
                            <i class="fa <?= $row[1] ?>" aria-hidden="true"></i>
                            <?php endif; ?> 
                            <?= $row[0] ?>
                        </a>
                    </li>
                    <?php endforeach; ?>
                    <li class="bt-1 nowrap">
                        <a href="<?= ADMIN_URL . '/user/logout' ?>">
                            <i class="fa fa-sign-out" aria-hidden="true"></i> <?= _l('Sign out') ?>
                        </a>
                    </li>
                </ul>
            </div>
            
            <div class="admin-content-wrapper">
                <?php Admin::renderHTMLContent(); ?>
            </div>
            
            <div class="clearfix"></div>
        </div>
        
        <?php else: ?>
            
            <?php Admin::renderHTMLContent(); ?>
        
        <?php endif; ?>

        <?= Template::include( 'footer' ) ?>
    </body>
</html>