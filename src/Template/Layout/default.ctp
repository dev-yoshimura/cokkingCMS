<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>レシピ管理</title>
    
    <?= $this->Html->meta('icon') ?>
    
    <?= $this->Html->css('bootstrap.min.css'); ?>
    <?= $this->Html->css('flash.css'); ?>
    
    <?= $this->fetch('css'); ?>
    
</head>
<body>
    
    <?= $this->fetch('content'); ?>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <?= $this->Html->script('bootstrap.min'); ?>
    <?= $this->Html->script('common'); ?>
    <?= $this->fetch('script'); ?>
</body>
</html>