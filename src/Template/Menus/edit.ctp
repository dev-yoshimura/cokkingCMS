<?= $this->Html->css('common.css',['block' => 'css']); ?>

<?= $this->element('nav'); ?>
<?= $this->Flash->render(); ?>

<article>
    <?= $this->Form->create($entities, ['type' => 'file',
                                        'url' => ['controller' => 'menus', 'action' => 'edit'],
                                        'class' => ['form-horizontal']
                            ]); ?>
        <?= $this->Form->hidden('Menu.modified'); ?>
        <?= $this->element('menuEdit'); ?>
    <?= $this->Form->end(); ?>
    
    <div style="height:25px"></div>
    <div class="row">
        <div class="col-xs-1 col-xs-push-11">
            <button id="btnEntry" class="btn btn-success btn-lg" >更新</button>
        </div>
    </div>

    <!-- ダイアログ -->
    <?= $this->element('modal', ['bodyText' => '更新しますか？']); ?>
</article>