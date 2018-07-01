<?= $this->Html->css('common.css',['block' => 'css']); ?>
<?= $this->Html->css('user.css', ['block' => 'css']); ?>

<?= $this->element('nav'); ?>
<?= $this->Flash->render(); ?>

<article>
    <?= $this->Form->create($user,['type'  => 'post', 
                                   'url'   => ['controller' => 'users', 'action' => 'edit'],
                                   'class' => ['form-horizontal']
                            ]); ?>
        <?= $this->Form->hidden('modified'); ?>
        <div class="row" style="margin-top: 50px;"></div>
        <div class="form-group">
            <label class="col-xs-3 control-label" for="name">名前</label>
            <div class="col-xs-9">
                <?= $this->Form->text('name',['class' => 'form-control', 'maxlength' => '255']); ?>
                <?= $this->Form->error('name'); ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-3 control-label" for="email">メールアドレス</label>
            <div class="col-xs-9">
                <?= $this->Form->email('email', ['class' => 'form-control', 'maxlength' => '255']); ?>
                <?= $this->Form->error('email'); ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-3 control-label" for="password">パスワード</label>
            <div class="col-xs-9">
                <?= $this->Form->password('password', ['class' => 'form-control', 'maxlength' => '255']); ?>
                <?= $this->Form->error('password'); ?>
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-3 control-label" for="password2">パスワードの確認入力</label>
            <div class="col-xs-9">
                <?= $this->Form->password('password2', ['class' => 'form-control', 'maxlength' => '255']); ?>
            </div>
        </div>
    <?= $this->Form->end(); ?>
    
    <div class="row">
        <div class="col-xs-1 col-xs-push-11">
            <button id="btnEntry"  class="btn btn-success btn-lg">更新</button>
        </div>
    </div>
    
    <!-- ダイアログ -->
    <?= $this->element('modal', ['bodyText' => '更新しますか？']); ?>
</article>

<?php

    $this->Html->scriptStart(['block' => true]);
    
    // 入力チェックURL
    $validateURL = $this->Url->build(['controller' => 'Users', 'action' => 'validate'], false);
    echo sprintf("var g_validateURL = '%s';", $validateURL);

    // ErrorURL
    $errorUrl = $this->Url->build(['controller' => 'Error', 'action' => 'index'], false);
    echo sprintf("var g_errorURL = '%s';", $errorUrl);

    $this->Html->scriptEnd();
?>

<?= $this->Html->script('user',['block' => 'script']); ?>