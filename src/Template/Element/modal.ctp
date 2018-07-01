<?= $this->Html->css('loading.css',['block' => 'css']) ?>

<!-- ローディング -->
<div id="loader-bg">
  <div id="loader">
    <?= $this->Html->image('loading.gif', ['width' => '80px', 'height' => '80px', 'alt' => 'Now Saving...']); ?>
    <p>Now Saving...</p>
  </div>
</div>

<!-- ダイアログ -->
<div class="modal fade" id="confirmation">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body">
                <span><?= h($bodyText); ?></span>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary btn-sm" id="btnModalYes"  data-dismiss="modal">はい</button>
                <button class="btn btn-primary btn-sm" data-dismiss="modal">いいえ</button>
            </div>
        </div>
    </div>
</div>

<?= $this->Html->script('modal', ['block' => 'script']); ?>