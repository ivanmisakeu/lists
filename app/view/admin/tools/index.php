
<h1>
    <i class="fa fa-wrench color" aria-hidden="true"></i>
    <?= _l( 'Tools' ) ?>
</h1>

<div class="clearfix"></div>

<div>
    <a href="<?= APP_URL ?>/script/db-backup?return_url=<?= ADMIN_URL ?>/tools&format_result" class="btn btn-default">
        <i class="fa fa-database color" aria-hidden="true"></i> <?= _l( 'Run database backup' ); ?>
    </a>

    <?php

    $last_db_backup = Settings::getLastDbBackup();
    
    if ($last_db_backup):
        
        ?>
    
        <br /><br />
        <?= _l( 'Last backup file' ) ?>: 
        <a href="<?= $last_db_backup->link ?>">
            <i class="fa fa-download" aria-hidden="true"></i> <?= $last_db_backup->name ?>
        </a>
        
        <?php

    else:
        
        ?>
        
        <p><em><?= _l( 'DB backup has been not found' ); ?></em></p>
        
        <?php

    endif;
    ?>
        
</div>

<hr />

<div>
    <a href="<?= APP_URL ?>/script/db-update?return_url=<?= ADMIN_URL ?>/tools&format_result" class="btn btn-default">
        <i class="fa fa-database color" aria-hidden="true"></i> <?= _l( 'Run database migration' ); ?>
    </a>
    
    <br /><br />
    
    
    <?= _l('Last migration done') . ': '; ?>
    
    <?php $last_db_migration_done = Settings::getLastMigrationFileDone()['name']; ?>
    <a href="<?= APP_URL . '/app/sql/migration/' . $last_db_migration_done ?>">
        <i class="fa fa-download" aria-hidden="true"></i> <?= $last_db_migration_done ?>
    </a>
    
    <br />
    
    <?= _l('Last migration file') . ': '; ?>
    
    <?php $last_db_migration = Settings::getLastDbMigrationFile(); ?>
    <a href="<?= $last_db_migration->link ?>">
        <i class="fa fa-download" aria-hidden="true"></i> <?= $last_db_migration->name ?>
    </a>
</div>

<hr />

<div>
    <a href="<?= APP_URL ?>/script/find-missing-translations?return_url=<?= ADMIN_URL ?>/tools&format_result" class="btn btn-default">
        <i class="fa fa-flag-o color" aria-hidden="true"></i> <?= _l( 'Find missing translations' ); ?>
    </a>

    <br /><br />

    <?php
        $translations = array_merge( [Lang::LANG_DEFAULT], Lang::searchForTranslationFiles() );
    ?>
    <?= _l( 'Languages in application' ) . ': <strong>' . strtoupper(join(', ' , $translations )) . '</strong>' ?>
    
    <br /><br />
    
    <?= '<strong>' . strtoupper(APP_LANG) . ':</strong>'; ?>
        
    <br />
    
    <?= _l( 'Count of translations' ) . ': <strong>' . count( Lang::getLangArray() ) . '</strong>' ?>
    
    <br />
    
    <?= _l( 'Missing translations' ) . ': <strong>' . count( Lang::getMissingTranslations() ) . '</strong>' ?>
    
</div>

<hr />

<div>
    <a href="#" class="btn btn-default">
        <i class="fa fa-file-code-o color" aria-hidden="true"></i> <?= _l( 'Application logs' ); ?>
    </a>
</div>