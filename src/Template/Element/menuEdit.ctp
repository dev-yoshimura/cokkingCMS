<?= $this->Html->css('menu-edit.css', ['block' => 'css']); ?>

<?= $this->Form->hidden('Menu.id'); ?>
<div class="row">
    <div class="col-xs-12">
        <h3 class="page-header">メニュー</h2>
    </div>
</div>
<div class="form-group">
    <label class="col-xs-2 control-label">料理名</label>
    <div class="col-xs-10">
        <?= $this->Form->text('Menu.name', ['class' => 'form-control', 'maxlength' => '255', 'required' => 'true' ]); ?>
        <?= $this->Form->error('Menu.name') ?>
    </div>
</div>
<div class="form-group">
    <label class="col-xs-2 control-label">種別</label>
    <div class="col-xs-2">
        <?= $this->Form->select('Menu.type', $menuTypeSelect, ['id' => 'menuType', 'class' => 'form-control', 'default' => '0', 'empty' => '－']) ?>
        <?= $this->Form->error('Menu.type') ?>
    </div>
</div>    
<div class="form-group">
    <label class="col-xs-2 control-label">量</label>
    <div class="col-xs-2">
        <?= $this->Form->select('Menu.quantity', $menuQuantitySelect, ['id' => 'menuQuantity', 'class' => 'form-control', 'default' => '0', 'empty' => '－']) ?>
        <?= $this->Form->error('Menu.quantity') ?>
    </div>
    <label class="col-xs-2 control-label" style="text-align: left;padding-left: 0px;">人分</label>
</div>
<div class="form-group">
    <label class="col-xs-2 control-label">写真</label>
    <div class="col-xs-8">
        <img src="<?php 
            if(isset($entities)) {
                echo $this->Url->build(['controller' => 'Menus', 'action' => 'contents', $entities['Menu']['id']]);
            }
            else {
                echo '';
            }?>" class="img-responsive" id="preview">
        <?= $this->Form->error('Menu.image') ?>
    </div>
    <div class="col-xs-2 col-xs-push-1">
        <button class="btn btn-primary btn-lg" id="btnImage">選択</button>
        <?= $this->Form->file('Menu.image', ['id' => 'menuImage', 'accept' => 'image/jpeg']) ?>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <h3 class="page-header">材料</h2>
    </div>
</div>
<?php
    $count = 0;
    if (is_null($entities['Materoals'])) {
        echo $this->element('materialEdit', ['count' => $count]);
    }
    else {
        foreach ($entities['Materoals'] as $entitie) {
            echo $this->element('materialEdit', ['count' => $count]);
            $count++;
        }
    }
?>
<div class="row" id="addMaterial">
    <div class="col-xs-1 col-xs-push-11">
        <button class="btn btn-primary btn-lg" id="btnAddMaterial">追加</button>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <h3 class="page-header">作り方</h2>
    </div>
</div>

<?php
    $count = 0;
    if (is_null($entities['Recipes'])) {
        echo $this->element('recipeEdit', ['count' => $count]);
    } else {
        foreach ($entities['Recipes'] as $entitie) {
            echo $this->element('recipeEdit', ['count' => $count]);
            $count++;
        }
    }
?>
<div class="row" id="addRecipe">
    <div class="col-xs-1 col-xs-push-11">
        <button class="btn btn-primary btn-lg" id="btnAddRecipe">追加</button>
    </div>
</div>

<?php

    $this->Html->scriptStart(['block' => true]);
    
    // 入力チェックURL
    $validateURL = $this->Url->build(['controller' => 'Menus', 'action' => 'validate'], false);
    echo sprintf("var g_validateURL = '%s';", $validateURL);
    
    // ErrorURL
    $errorUrl = $this->Url->build(['controller' => 'Error', 'action' => 'index'], false);
    echo sprintf("var g_errorURL = '%s';", $errorUrl);

    // 材料入力項目
    $materialEdit = preg_replace('/(?:\n|\r|\r\n)/', '', $this->element('materialEdit', ['count' => 'count']));
    echo sprintf("var g_materialInput = '%s';", $materialEdit);

    // 作り方入力項目
    $recipeEdit = preg_replace('/(?:\n|\r|\r\n)/', '', $this->element('recipeEdit', ['count' => 'count']));
    echo sprintf("var g_recipeInput = '%s';", $recipeEdit);

    $this->Html->scriptEnd();
?>

<?= $this->Html->script('menu', ['block' => 'script']); ?>


