
<a href="<?= ADMIN_URL . '/tenant/add' ?>" class="pull-right btn btn-primary btn-sm m-t-sm">
    <i class="fa fa-plus" aria-hidden="true"></i> <?= _l('Add') ?>
</a>

<h1>
    <i class="fa fa-list-ul color" aria-hidden="true"></i>
    <?= _l( 'Lists' ) ?>
</h1>

<div class="clearfix"></div>

<?php if( count($tenants) ): ?>

    <table class="table table-admin table-bordered table-striped">
        <thead>
            <tr>
                <th></th>
                <th><?= _l('Address') ?></th>
                <th><?= _l('List title') ?></th>
                <th><?= _('Date created') ?></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach( $tenants as $tenant): ?>
            <tr>
                <td align="right">
                #<?= $tenant['id'] ?>
                </td>
                <td>
                    <a href="<?= APP_URL . '/' . $tenant['name'] ?>" target="_blank">
                        <i class="fa fa-link" aria-hidden="true"></i> <?= $tenant['name'] ?>
                    </a>
                </td>
                <td>
                    <?= $tenant['title'] ?>
                </td>
                <td>
                    <?= $tenant['created']->format(APP_HUMAN_DATE) ?>
                </td>
                <td>
                    <a class="btn btn-xs btn-primary" href="<?= ADMIN_URL . '/tenant/edit/' . $tenant['id'] ?>">
                        <i class="fa fa-pencil" aria-hidden="true"></i> <?= _l('Edit') ?>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

<?php else: ?>

    <p><em><?= _l('List is empty'); ?></em></p>
    
<?php endif; ?>
