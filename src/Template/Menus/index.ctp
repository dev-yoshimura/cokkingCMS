<?= $this->Html->css('common.css', ['block' => 'css']); ?>
<?= $this->Html->css('menu-list.css', ['block' => 'css']); ?>

<?= $this->element('nav'); ?>
<?= $this->Flash->render(); ?>

<article>
    <div class="row" style="margin-top: 50px;"></div>
    <table class="table menu__table">
        <thead>
            <tr>
                <th class="col-xs-1"></th>
                <th class="col-xs-1">イメージ</th>
                <th class="col-xs-10"><?= $this->Paginator->sort('hiragana', 'レシピ名'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($menus as $menu): ?>
                <tr>
                    <td>
                        <?= $this->Form->create(null, ['type' => 'post', 'url' => ['controller' => 'menus', 'action' => 'edit'] ]);?>
                            <button class="btn btn-primary btn-block">変更</button>
                            <?= $this->Form->hidden('id', ['value' => $menu->id]); ?>
                        <?= $this->Form->end(); ?>
                    </td>
                    <td>
                        <img src="<?= $this->Url->build(['controller' => 'Menus', 'action' => 'contents', $menu->id]); ?>" class="img-responsive menu__img">
                    </td>
                    <td>
                        <?= h($menu->name); ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="text-center">
        <ul class="pagination">
            <?= $this->Paginator->numbers(); ?>
        </ul>      
    </div>

</article>