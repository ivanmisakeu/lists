
<h1>
    <i class="fa fa-list-ul color" aria-hidden="true"></i>
    <?= _l( 'Add new list' ) ?>
</h1>

<form action="<?= APP_URL ?>/doTenant/addNewTenant" method="post" autocomplete="off">
    
    <?php Template::render('tenant/@inc/form'); ?>
    
</form>