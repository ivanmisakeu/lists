
<a href="<?= ADMIN_URL . '/items/add/' . $tenant['id'] ?>" class="pull-right btn btn-primary btn-sm m-t-sm">
    <i class="fa fa-plus" aria-hidden="true"></i> <?= _l('Add') ?>
</a>

<h1>
    <i class="fa fa-list-ul color" aria-hidden="true"></i>
    <?= _l( 'Items' ) ?>
    
    <small>
        &gt;
        <i class="fa fa-user-o" aria-hidden="true"></i>
        <?= $tenant['name'] ?>
    </small>
</h1>

<div class="clearfix"></div>

<?php if( count($items) ): ?>

    <table class="table table-admin table-bordered table-striped">
        <thead>
            <tr>
                <th></th>
                <th><?= _l('Name') ?></th>
                <th><?= _('Date created') ?></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach( $items as $item): ?>
            <tr>
                <td width="50" align="right">
                    #<?= $item['id'] ?>
                </td>
                <td class="nowrap"  >
                    <?= $item['name'] ?>
                </td>
                <td width="140" class="nowrap">
                    <?= $item['created']->format(APP_HUMAN_DATE) ?>
                </td>
                <td class="nowrap">
                    
                    <a class="btn btn-xs btn-primary m-r-sm" href="<?= ADMIN_URL . '/item/edit/' . $item['id'] ?>">
                        <i class="fa fa-pencil" aria-hidden="true"></i> <?= _l('Edit') ?>
                    </a>
                    
                    <a onClick="
                        return _app.modals.removeItem_admin(<?= $item['id'] ?>,
                            '<?= $item['name'] ?>');" 
                        class="btn btn-xs btn-danger" 
                        href="#">
                            <i class="fa fa-trash" aria-hidden="true"></i> <?= _l('Delete') ?>
                    </a>
                    
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php Template::render('items/modals/delete'); ?>

<?php else: ?>

    <p><em><?= _l('List is empty'); ?></em></p>
    <p>&nbsp;</p>
    
<?php endif; ?>

<a href="<?= ADMIN_URL . '/tenant' ?>" class="btn btn-default m-r-xs">
    <i class="fa fa-arrow-left" aria-hidden="true"></i> <?= _l('Back') ?>
</a>
