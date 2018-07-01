<!--レイアウト側で$this->fetch('css')した場所にcss設定-->
<?= $this->Html->css('login.css', ['block' => true]); ?>

<?= $this->Flash->render(); ?>

<div class="container">
    <?= $this->Form->create(null, [ 'type'  => 'post', 
                                    'url'   => ['controller' => 'users', 'action' => 'login'],
                                    'class' => ['login']
                              ]); ?>
        <h2 class="login__heading">ログイン</h2>

        <input type="email" id="email" name="email" class="login__email" placeholder="Email address" required autofocus>
        <input type="password" id="password" name="password" class="login__password" placeholder="Password" required>
        <button class="btn btn-lg btn-primary btn-block" type="submit">ログイン</button>
    <?= $this->Form->end(); ?>
</div>