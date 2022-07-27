
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
                <th><?= _l('Active') ?></th>
                <th><?= _('Date created') ?></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach( $tenants as $tenant): ?>
            <tr>
                <td width="50" align="right">
                    #<?= $tenant['id'] ?>
                </td>
                <td class="nowrap"  >
                    <a href="<?= APP_URL . '/' . $tenant['name'] ?>" target="_blank">
                        <i class="fa fa-link" aria-hidden="true"></i> <?= $tenant['name'] ?>
                    </a>
                </td>
                <td>
                    <?= $tenant['title'] ? $tenant['title'] : '--' ?>
                </td>
                <td width="60" align="center" class="<?= $tenant['active'] ? 'text-success' : '' ?>">
                    <i class="fa <?= $tenant['active'] ? 'fa-check' : 'fa-times' ?>" aria-hidden="true"></i>
                </td>
                <td width="140" class="nowrap">
                    <?= $tenant['created']->format(APP_HUMAN_DATE) ?>
                </td>
                <td class="nowrap">
                    
                    <a class="btn btn-xs btn-primary" href="<?= ADMIN_URL . '/tenant/edit/' . $tenant['id'] ?>">
                        <i class="fa fa-pencil" aria-hidden="true"></i> <?= _l('Edit') ?>
                    </a>
                    
                    <?php if( Tenant::TENANT_ACTIVE == $tenant['active']): ?>
                    
                        <a onClick="
                            return _app.modals.disableTenant_admin(<?= $tenant['id'] ?>,
                                '<?= $tenant['name'] ?>',
                                '<?= $tenant['title'] ? $tenant['title'] : _l( 'Shopping list' ) ?>');" 
                            class="btn btn-xs btn-danger" 
                            href="#">
                                <i class="fa fa-times" aria-hidden="true"></i> <?= _l('Disable') ?>
                        </a>
                    
                    <?php else: ?>
                    
                        <a onClick="
                            return _app.modals.enableTenant_admin(<?= $tenant['id'] ?>,
                                '<?= $tenant['name'] ?>',
                                '<?= $tenant['title'] ? $tenant['title'] : _l( 'Shopping list' ) ?>');" 
                            class="btn btn-xs btn-success" 
                            href="#">
                                <i class="fa fa-check" aria-hidden="true"></i> <?= _l('Enable') ?>
                        </a>
                    
                    <?php endif ; ?>
                    
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php Template::render('tenant/modals/disable'); ?>
    <?php Template::render('tenant/modals/enable'); ?>

<?php else: ?>

    <p><em><?= _l('List is empty'); ?></em></p>
    
<?php endif; ?>
