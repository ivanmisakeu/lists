

<div  id="configTenantModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            
            <form action="<?= APP_URL ?>/doTenant/configList/" method="post">
                
                <?= Helper::captcha_get(); ?>
                
                <div class="modal-header">
                    <button type="button" class="close" onClick="$('#configTenantModal').hide();">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">
                        <i class="fa fa-cogs" aria-hidden="true"></i> <strong><?= _l( 'List settings' ) ?></strong>
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="tenant_name"><?= _l( 'Name of list' ); ?></label>
                        <input type="text" name="tenant_name" id="tenant_name" value="" autocomplete="off" class="form-control" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">
                        <i class="fa fa-floppy-o" aria-hidden="true"></i> <?= _l( 'Save' ) ?>
                    </button>
                    <button type="button" class="btn btn-danger pull-left" onClick="$('#configTenantModal').hide();">
                        <i class="fa fa-times" aria-hidden="true"></i> <?= _l( 'Cancel' ) ?>
                    </button>
                </div>
                
                <input type="hidden" name="tenant_id" value="0" />
                
            </form>
            
        </div>
    </div>
</div>