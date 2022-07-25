
<h1>
    <i class="fa fa-user-o color" aria-hidden="true"></i>
    <?= _l( 'Add new user' ) ?>
</h1>

<form action="<?= APP_URL ?>/doUser/addNewUser" method="post" autocomplete="off">
    
    <?= Helper::captcha_get(); ?>
     
    <div class="form-group">
        <label for="mail" class="required"><?= _l('Email address'); ?></label>
        <input type="text" name="mail" class="form-control required" autocomplete="off" />
    </div>
    
    <div class="form-group">
        <label for="password" class="required"><?= _l('Password'); ?></label>
        <input type="password" name="password" class="form-control required" autocomplete="off" />
        <small style="margin-top: 5px;" class="pull-right">
            <a href="#" onClick="$('[name=password]').val( _app.string.random_string() ).attr('type','text'); return false;" class="btn btn-xs btn-default">
                <i class="fa fa-random" aria-hidden="true"></i> <?= _l('generate') ?>
            </a>
        </small>
        <small>
            <em><?= _l('8 characters min.') ?></em>
        </small>
        <div class="clearfix"></div>
    </div>
        
    <input type="hidden" name="redirect_url" value="<?= ADMIN_URL ?>/user/add" />
    
    <button class="btn btn-primary">
        <i class="fa fa-plus" aria-hidden="true"></i> <?= _l( 'Create' ) ?>
    </button>
    
</form>