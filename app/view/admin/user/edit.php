
<h1>
    <i class="fa fa-user-o color" aria-hidden="true"></i>
    <?= _l( 'Edit user' ) . ' ' . $user['name'] ?>
</h1>

<form action="<?= APP_URL ?>/doUser/editUser/<?= $user['id'] ?>" method="post" autocomplete="off">
    
    <?= Helper::captcha_get(); ?>
     
    <div class="form-group">
        <label for="name" class="required"><?= _l('Nickname'); ?></label>
        <input type="text" name="name" class="form-control required" autocomplete="off" value="<?= $user['name'] ?>" />
    </div>
    
    <div class="form-group">
        <label for="mail" class="required"><?= _l('Email address'); ?></label>
        <input type="text" name="mail" class="form-control required" autocomplete="off" value="<?= $user['mail'] ?>" />
    </div>
    
    <div class="form-group">
        <label for="admin"><?= _l('Is admin'); ?></label>
        <select name="admin" size="1" class="form-control">
            <option value="0" <?= $user['admin'] == 0 ? 'selected="selected"':'' ?>><?= _l('No') ?></option>
            <option value="1" <?= $user['admin'] == 1 ? 'selected="selected"':'' ?>><?= _l('Yes') ?></option>
        </select>
    </div>
    
    <div class="form-group">
        <label for="password"><?= _l('Password'); ?></label>
        <input type="password" name="password" class="form-control" autocomplete="new-password" />
        <small style="margin-top: 5px;" class="pull-right">
            <a href="#" onClick="$('[name=password]').val( _app.string.random_string() ).attr('type','text'); return false;" class="btn btn-xs btn-default">
                <i class="fa fa-random" aria-hidden="true"></i> <?= _l('generate') ?>
            </a>
        </small>
        <small>
            <em><?= _l("Leave blank if you don't want to change") ?></em>
        </small>
        <div class="clearfix"></div>
    </div>
        
    <input type="hidden" name="redirect_url" value="<?= ADMIN_URL ?>/user" />
    
    <button class="btn btn-primary">
        <i class="fa fa-floppy-o" aria-hidden="true"></i> <?= _l( 'Save' ) ?>
    </button>
    
</form>