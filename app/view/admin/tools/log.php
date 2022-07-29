
<h1>
    <i class="fa fa-file-code-o color" aria-hidden="true"></i>
    <?= _l( 'Application logs' ) ?>
</h1>
<h4>
    <?= $type ?>
    <?php if( count($log_files) ): echo ' (' . count($log_files) . ')'; endif; ?>
</h4>

<div class="clearfix"></div>

<?php if( count($log_files) ): ?>

    <?php foreach( $log_files as $file ): ?>

    <a href="<?= APP_URL . '/app/logs/' . $file ?>" style="display:block; margin: 0px 0px 5px 0px;" download="<?= $file ?>">
        <i class="fa fa-download" aria-hidden="true"></i> <?= $file ?>
    </a>
    
    <?php endforeach; ?>

<?php else: ?>

    <p><em><?= _l('List is empty'); ?></em></p>
    
<?php endif; ?>

<br />
<a href="<?= ADMIN_URL ?>/tools" class="btn btn-sm btn-primary">
    <i class="fa fa-arrow-left" aria-hidden="true"></i> <?= _l('Go back') ?>
</a>