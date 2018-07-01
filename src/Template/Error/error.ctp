<?php 
    use Cake\Core\Configure;

    Configure::load('app', 'default', false);

    $imageBaseUrl = $this->Url->build('/'.Configure::read('App.imageBaseUrl'), true);
?>
<div class="error">
    <div>
        <div class="error__title">
            <img src=<?= h($imageBaseUrl."/gear.png"); ?>>
            <div>
                システムエラーが発生しました。<br>
                大変申し訳ございません。
            </div>
        </div>

        <div class="error__contents">
            サーバが混み合っているか、プログラム誤作動によるエラーが発生しました。<br>
            早急に対応させて頂きます。ご迷惑お掛け致しまして、大変申し訳ございません。<br>
            しばらく経ってから再度ご利用くださいますようお願い申し上げます。<br>
        </div>
    </div>
</div>