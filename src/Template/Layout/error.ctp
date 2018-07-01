<?php 
    use Cake\Core\Configure;

    Configure::load('app', 'default', false);

    // App.imageBaseUrl
    $webroot = $this->Url->build('/'.Configure::read('App.webroot'), true);
    $cssBaseUrl = $this->Url->build('/'.Configure::read('App.cssBaseUrl'), true);
?>
<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>レシピ管理</title>
    
    <link href=<?= h($webroot."/favicon.ico"); ?> type="image/x-icon" rel="icon">
    <link href=<?= h($webroot."/favicon.ico"); ?> type="image/x-icon" rel="shortcut icon">

    <link rel="stylesheet" href=<?= h($cssBaseUrl."bootstrap.min.css"); ?>>
    <link rel="stylesheet" href=<?= h($cssBaseUrl."error.css"); ?>>
</head>
<body>
    <?= $this->fetch('content'); ?>
</body>
</html>
