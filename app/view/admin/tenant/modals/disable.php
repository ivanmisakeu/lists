
<div  id="disableTenantModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
           
            <form action="<?= APP_URL ?>/doTenant/disableTenant/" method="post">
                
                <?= Helper::captcha_get(); ?>
                
                <div class="modal-header">
                    <button type="button" class="close" onClick="$('#disableTenantModal').hide();">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">
                        <i class="fa fa-trash-o" aria-hidden="true"></i> <strong><?= _l( 'Realy disable tenant?' ) ?></strong>
                    </h4>
                    <br />
                    <p>-- tenant name --</p>
                </div>
                
                <div class="modal-footer">
                    <button class="btn btn-primary">
                        <i class="fa fa-check" aria-hidden="true"></i> <?= _l( 'Yes' ) ?>
                    </button>
                    <button type="button" class="btn btn-danger pull-left" onClick="$('#disableTenantModal').hide();">
                        <i class="fa fa-times" aria-hidden="true"></i> <?= _l( 'No' ) ?>
                    </button>
                </div>
            
                <input type="hidden" name="redirect_url" value="<?= ADMIN_URL . '/tenant' ?>" />
                <input type="hidden" name="id_tenant" value="" />
            </form>
            
        </div>
    </div>
</div>