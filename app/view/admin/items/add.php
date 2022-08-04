
<h1>
    <i class="fa fa-list-ul color" aria-hidden="true"></i>
    <?= _l( 'Add item' ) ?>
    
    <small>
        &gt;
        <i class="fa fa-user-o" aria-hidden="true"></i>
        <?= $tenant['name'] ?>
    </small>
</h1>

<form action="<?= APP_URL ?>/doItems/addItem" method="post" autocomplete="off">

    <?php 
        Template::assign( 'tenant', $tenant );
        Template::render('items/@inc/form'); 
    ?>
    
</form>