

<div  id="removeItemModal" class="modal" tabindex="-1" role="dialog" data-url="<?= APP_URL ?>/doItems/removeItem/">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" onClick="$('#removeItemModal').hide();">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">
                    <strong><?= _l( 'Realy delete item?' ) ?></strong>
                </h4>
                <br />
                <p>-- item name --</p>
            </div>
            <!--
                <div class="modal-body">
                  <p>Modal body text goes here.</p>
                </div>
            -->
            <div class="modal-footer">
                <a href="#" class="btn btn-primary">
                    <i class="fa fa-check" aria-hidden="true"></i> <?= _l( 'Yes, delete' ) ?>
                </a>
                <button type="button" class="btn btn-danger pull-left" onClick="$('#removeItemModal').hide();">
                    <i class="fa fa-times" aria-hidden="true"></i> <?= _l( 'No' ) ?>
                </button>
            </div>
        </div>
    </div>
</div>