
<?php Helper::flash_show(); ?>

<script src="<?= APP_URL ?>/resources/js/jquery-2.2.4.min.js"></script>
<script src="<?= APP_URL ?>/resources/js/main.js<?= Helper::res_timestamp( 'js/main.js' ); ?>"></script>
<script>
    _app.lang.translations = {
        'Please fill all required fields': '<?= _l( 'Please fill all required fields' ) ?>',
        'Admin': '<?= _l( 'Admin' ) ?>',
    };
    
    <?php if( Template::$HTML_TITLE ): ?>$('title').text('<?= Template::$HTML_TITLE . ' | ' . _l( 'Admin' ) ?>');<?php endif; ?>
</script>