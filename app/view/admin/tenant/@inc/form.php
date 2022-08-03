
<?= Helper::captcha_get(); ?>
     
<div class="form-group">
    <label for="name" class="required"><?= _l('Link'); ?></label>
    <input type="text" name="name" class="form-control required" autocomplete="nope" value="<?= isset($tenant) ? $tenant['name'] : '' ?>" onChange="$(this).val( _app.string.make_url( $(this).val()) );" />
</div>

<div class="form-group">
    <label for="title"><?= _l('Title'); ?></label>
    <input type="text" name="title" class="form-control" autocomplete="nope" value="<?= isset($tenant) && $tenant['title'] ? $tenant['title'] : _l( 'Shopping list' ) ?>" />
</div>


<?php if( isset($tenant) ): ?>
<input type="hidden" name="self_url" value="<?= ADMIN_URL . '/tenant/edit/' . $tenant['id'] ?>" />
<?php else: ?>
<input type="hidden" name="self_url" value="<?= ADMIN_URL . '/tenant/add' ?>" />
<?php endif; ?>
<input type="hidden" name="redirect_url" value="<?= ADMIN_URL ?>/tenant" />
<input type="hidden" name="id_tenant" value="<?= isset($tenant) ? $tenant['id'] : '' ?>" />

<a href="<?= ADMIN_URL . '/tenant' ?>" class="btn btn-default m-r-xs">
    <i class="fa fa-arrow-left" aria-hidden="true"></i> <?= _l('Back') ?>
</a>

<button class="btn btn-primary">
    
    <?php if( isset($tenant) ): ?>
    <i class="fa fa-floppy-o" aria-hidden="true"></i> <?= _l( 'Save' ) ?>
    <?php else: ?>
    <i class="fa fa-plus" aria-hidden="true"></i> <?= _l( 'Create' ) ?>
    <?php endif; ?>
    
</button>