
<div class="admin-header-wrapper">
    <div class="pull-right">
         <?= User::$CURRENT_USER['name'] ?> 
        <a href="<?= ADMIN_URL . '/user/logout' ?>" title="<?= _l('Sign out') ?>">
            <i class="fa fa-sign-out" aria-hidden="true"></i>
        </a>
    </div>
    
    <a href="<?= ADMIN_URL ?>" class="logo-wrapper">
        <div class="logo"></div> <?= _l( 'Admin' ) ?>
    </a>
</div>
