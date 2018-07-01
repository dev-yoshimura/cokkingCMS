<div class="form-group" name="material">
    <label class="col-xs-2 control-label">種別／材料／分量</label>
    <div class="col-xs-2">
        <?= $this->Form->select('Materoals.'.$count.'.type', $materialTypeSelect, ['class' => 'form-control', 'default' => '0', 'empty' => '－']); ?>
        <?= $this->Form->error('Materoals.'.$count.'.type'); ?>
    </div>
    <div class="col-xs-5">
        <?= $this->Form->text('Materoals.'.$count.'.name', ['class' => 'form-control',  "maxlength" => "255", "required" => "true" ] ); ?>
        <?= $this->Form->error('Materoals.'.$count.'.name'); ?>
    </div>
    <div class="col-xs-2">
        <?= $this->Form->text('Materoals.'.$count.'.quantity', ['class' => 'form-control', "maxlength" => "255", "required" => "true" ] ); ?>
        <?= $this->Form->error('Materoals.'.$count.'.quantity'); ?>
    </div>
    <div class="col-xs-1">
        <button class="btn btn-primary btn-lg" name="btnDeleteMaterial">削除</button>
    </div>
</div>