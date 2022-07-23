
<h1><?= _l( 'New tenant' ) ?></h1>

<form action="<?= APP_URL ?>/doTenant/createTenant" method="post">
    
    <?= Helper::captcha_get(); ?>

    <div class="form-group">
        <input type="text" name="name" class="form-control required" placeholder="<?= _l( 'New tenant name' ) ?>" autocomplete="off" />
    </div>
    
    <button class="btn btn-primary">
        <i class="fa fa-plus" aria-hidden="true"></i> <?= _l( 'Create' ) ?>
    </button>
    
</form>