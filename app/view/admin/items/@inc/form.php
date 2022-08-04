
<?= Helper::captcha_get(); ?>
     
<div class="form-group">
    <label for="name" class="required"><?= _l('Name'); ?></label>
    <input type="text" name="name" class="form-control required" autocomplete="nope" value="<?= isset($item) ? $item['name'] : '' ?>" />
</div>

<?php if( isset($item) ): ?>
<input type="hidden" name="self_url" value="<?= ADMIN_URL . '/items/view/' . $tenant['id'] ?>" />
<?php else: ?>
<input type="hidden" name="self_url" value="<?= ADMIN_URL . '/tenant/add' . $tenant['id'] ?> ?>" />
<?php endif; ?>
<input type="hidden" name="redirect_url" value="<?= ADMIN_URL ?>/items/view/<?= $tenant['name'] ?>" />
<input type="hidden" name="id_tenant" value="<?= $tenant['id'] ?>" />
<input type="hidden" name="id_item" value="<?= isset($item) ? $item['id'] : '' ?>" />

<a href="<?= ADMIN_URL . '/items/view/' . $tenant['name'] ?>" class="btn btn-default m-r-xs">
    <i class="fa fa-arrow-left" aria-hidden="true"></i> <?= _l('Back') ?>
</a>

<button class="btn btn-primary">
    
    <?php if( isset($item) ): ?>
    <i class="fa fa-floppy-o" aria-hidden="true"></i> <?= _l( 'Save' ) ?>
    <?php else: ?>
    <i class="fa fa-plus" aria-hidden="true"></i> <?= _l( 'Create' ) ?>
    <?php endif; ?>
    
</button>