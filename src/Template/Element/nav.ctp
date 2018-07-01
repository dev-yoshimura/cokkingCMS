<?= $this->Html->css('nav.css',['block' => 'css']) ?>

<section class="navigation">
    <div class="nav-container">
        <div class="brand">
            <h3><?= h($title); ?></h3>
        </div>
        <nav>
            <div class="nav-mobile">
                <a id="nav-toggle" href="#!">
                    <span></span>
                </a>
            </div>
            <ul class="nav-list">
                <li>
                    <?= $this->Html->link('Home', ['controller' => 'Menus', 'action' => 'index']) ?>
                </li>
                <li>
                    <?= $this->Html->link('Create', ['controller' => 'Menus', 'action' => 'create']); ?>
                </li>
                <li>
                    <?= $this->Html->link('User', ['controller' => 'Users', 'action' => 'edit']); ?>
                </li>
                <li>
                    <?= $this->Html->link('Logout', ['controller' => 'Users', 'action' => 'logout']); ?>
                </li>
            </ul>
        </nav>
    </div>
</section>

<?= $this->Html->script('nav', ['block' => 'script']); ?>