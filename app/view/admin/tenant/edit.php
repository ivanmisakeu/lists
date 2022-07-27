
<h1>
    <i class="fa fa-list-ul color" aria-hidden="true"></i>
    <?= _l( 'Edit list' ) . ' #' . $tenant['id'] ?>
</h1>

<form action="<?= APP_URL ?>/doTenant/editTenant" method="post" autocomplete="off">

    <?php 
        Template::assign( 'tenant', $tenant );
        Template::render('tenant/@inc/form'); 
    ?>
    
</form>