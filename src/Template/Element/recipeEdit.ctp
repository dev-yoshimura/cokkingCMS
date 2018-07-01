<div class="form-group" name="recipe">
    <label class="col-xs-2 control-label"><?= is_numeric($count)? $count + 1 : 1; ?></label>
    <div class="col-xs-9">
        <?= $this->Form->text('Recipes.'.$count.'.detail', ['class' => 'form-control', "maxlength" => "255", "required" => "true" ] ); ?>
        <?= $this->Form->error('Recipes.'.$count.'.detail') ?>
    </div>
    <div class="col-xs-1">
        <button class="btn btn-primary btn-lg" name="btnDeleteRecipe">削除</button>
    </div>
</div>