
<a href="#" class="items-list-config-pointer" onClick="return _app.modals.configItemList( <?= $tenant['id'] ?> , '<?= $tenant['title'] ? $tenant['title'] : _l( 'Shopping list' ) ?>');">
    <i class="fa fa-cogs" aria-hidden="true"></i>
</a>

<h1>
    <div class="logo"></div>
    <?= $tenant['title'] ? $tenant['title'] : _l( 'Shopping list' ) ?>
</h1>

<form action="<?= APP_URL ?>/doItems/createItem" method="post">
    
    <?= Helper::captcha_get(); ?>

    <div class="form-group">
        <input type="text" name="name" class="form-control required" placeholder="<?= _l( 'Add new item' ) ?>" autocomplete="off" />
        <input type="hidden" name="id_tenant" value="<?= $tenant[ 'id' ] ?>" />
    </div>
    <button class="btn btn-primary">
        <i class="fa fa-plus" aria-hidden="true"></i> <?= _l( 'Add' ) ?>
    </button>

</form>

<hr />

<?php if ( count( $items ) ): ?>

    <table class="table table-striped table-bordered">
    <?php foreach ( $items as $item ): ?>
            <tr>
                <td>
                    <div class="link" onClick="return _app.modals.removeItem(<?= $item->id ?>, '<?= $item->name ?>');">
                        <i class="fa fa-trash-o pull-right" aria-hidden="true"></i>
                        <?= $item->name ?>
                    </div>
                </td>
            </tr>
    <?php endforeach; ?>
    </table>

    <?php Template::render('item/modals/delete'); ?>
    <?php Template::render('item/modals/config'); ?>

<?php else: ?>

    <p>
        <em><?= _l( 'List of items is empty.' ) ?></em>
    </p>

<?php endif; ?>