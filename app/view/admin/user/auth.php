
<div class="sign-in-wrapper">
    
    <h1 class="m-t-0">
        <i class="fa fa-unlock-alt color" aria-hidden="true"></i>
        <?= _l( 'Login' ) ?>
    </h1>

    <form action="<?= APP_URL ?>/doUser/authentificateUser" method="post" autocomplete="off">

        <?= Helper::captcha_get(); ?>

        <div class="form-group">
            <label for="mail"><?= _l( 'Email address' ); ?></label>
            <input type="text" name="mail" class="form-control required" autocomplete="off" />
        </div>

        <div class="form-group">
            <label for="password"><?= _l( 'Password' ); ?></label>
            <input type="password" name="password" class="form-control required" autocomplete="off" />
        </div>

        <input type="hidden" name="redirect_url" value="<?= ADMIN_URL ?>" />

        <button class="btn btn-primary">
            <i class="fa fa-plus" aria-hidden="true"></i> <?= _l( 'Sign in' ) ?>
        </button>

    </form>
    
</div>