
<a href="<?= ADMIN_URL . '/user/add' ?>" class="pull-right btn btn-primary btn-sm m-t-sm">
    <i class="fa fa-plus" aria-hidden="true"></i> <?= _l('Add') ?>
</a>

<h1>
    <i class="fa fa-user-o color" aria-hidden="true"></i>
    <?= _l( 'Users' ) ?>
</h1>

<div class="clearfix"></div>

<?php if( count($users) ): ?>

    <table class="table table-admin table-bordered table-striped">
        <thead>
            <tr>
                <th></th>
                <th><?= _l('E-mail') ?></th>
                <th><?= _l('Is admin') ?></th>
                <th><?= _('Date created') ?></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach( $users as $user): ?>
            <tr>
                <td align="right">
                #<?= $user['id'] ?>
                </td>
                <td>
                    <a href="mailto:<?= $user['mail'] ?>"><?= $user['mail'] ?></a>
                </td>
                <td align="center" class="<?= $user['admin'] ? 'text-success' : '' ?>">
                    <i class="fa <?= $user['admin'] ? 'fa-check' : 'fa-times' ?>" aria-hidden="true"></i>
                </td>
                <td>
                    <?= $user['created']->format(APP_HUMAN_DATE) ?>
                </td>
                <td>
                    <a class="btn btn-xs btn-primary" href="<?= ADMIN_URL . '/user/edit/' . $user['id'] ?>">
                        <i class="fa fa-pencil" aria-hidden="true"></i> <?= _l('Edit') ?>
                    </a>
                    <a onClick="return _app.modals.removeUser_admin(<?= $user['id'] ?>,'<?= $user['name'] ?>','<?= $user['mail'] ?>');" class="btn btn-xs btn-danger" href="#">
                        <i class="fa fa-trash" aria-hidden="true"></i> <?= _l('Delete') ?>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php Template::render('user/modals/delete'); ?>

<?php else: ?>

    <p><em><?= _l('List is empty'); ?></em></p>
    
<?php endif; ?>
