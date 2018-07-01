<?= $this->Html->css('common.css',['block' => 'css']); ?>

<?= $this->element('nav'); ?>
<?= $this->Flash->render(); ?>

<article>
    <?= $this->Form->create($entities, ['type' => 'file',
                                        'url' => ['controller' => 'menus', 'action' => 'create'],
                                        'class' => ['form-horizontal']
                            ]); ?>
        <?= $this->element('menuEdit'); ?>
    <?= $this->Form->end(); ?>
    
    <div style="height:25px"></div>
    <div class="row">
        <div class="col-xs-1 col-xs-push-11">
            <button id="btnEntry" class="btn btn-success btn-lg" >登録</button>
        </div>
    </div>

    <!-- ダイアログ -->
    <?= $this->element('modal', ['bodyText' => '登録しますか？']); ?>
</article>