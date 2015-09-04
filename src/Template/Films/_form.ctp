<?php $this->Html->scriptStart(['block' => true]) ?>
<?= '
  $("#add-screening").on("click", function(e) {
    e.preventDefault();
    
    var lastFieldset = $(".screening-fieldset").last();
    var newFieldset = lastFieldset.clone().insertAfter(lastFieldset).hide().slideDown();
    
    newFieldset.find("input").each(function() {
      $(this).val("");
    });
    newFieldset.find("select").each(function() {
      $(this).find("option").removeAttr("selected");
    });
    newFieldset.find("[type=checkbox]").each(function() {
      $(this).attr("checked", false);
    });
    
    var key = newFieldset.find(".key");
    var keyVal = key.html();
    key.html(parseInt(keyVal) + 1);
  });
' ?>
<?php $this->Html->scriptEnd() ?>

<?= $this->Form->create($film) ?>
  
  <fieldset>
    <legend><h1>Add a Film</h1></legend>
    <?= $this->Form->hidden('id') ?>
    <?= $this->Form->input('title', ['placeholder' => 'Original Title', 'label' => false]) ?>
    
    <div class="row">
      <div class="col-xs-18">
        <?= $this->Form->input('translation.title', ['placeholder' => 'Translation', 'label' => false]) ?>
      </div>
      <div class="col-xs-6">
        <?= $this->Form->year('released', ['dateFormat' => 'Y', 'minYear' => 1900, 'maxYear' => date('Y'), 'default' => date('Y'), 'empty' => false]) ?>
      </div>
    </div>
    
    <?php foreach($film->screenings as $key => $screening): ?>
      <fieldset id="fieldset-<?= $key ?>" class="screening-fieldset">
        <div class="row">
          <div class="col-xs-1">
            <label class="key"><?= $key + 1 ?></label>
          </div>
          <div class="col-xs-8">
            <?= $this->Form->input("screenings.$key.screened", ['label' => false, 'type' => 'text']) ?>
          </div>
          <div class="col-xs-13">
            <?= $this->Form->input("screenings.$key.location_id", ['label' => false, 'options' => $locations, 'empty' => '--']) ?>
          </div>
          <div class="col-xs-2">
            <?= $this->Html->link(
              $this->Html->icon('minus'),
              ['#'],
              ['class' => 'btn btn-success remove-screening', 'id' => "remove-screening-$key", 'escape' => false]
            ) ?>
          </div>
        </div>
        <?= $this->Form->input("screenings.$key.viewers._ids", ['label' => 'With', 'options' => $viewers, 'multiple' => 'checkbox']) ?>
      </fieldset>
    <?php endforeach ?>
    
    <div class="pull-right">
      <?= $this->Html->link($this->Html->icon('plus'), ['#'], ['class' => 'btn btn-info', 'id' => 'add-screening', 'escape' => false]) ?>
    </div>
  
    <div class="submit">
      <?= $this->Form->submit('Save', ['class' => 'btn btn-primary']) ?>
    </div>
    
  </fieldset>

<?= $this->Form->end() ?>