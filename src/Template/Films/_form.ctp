<h1>Add a Film</h1>

<?php $this->Html->scriptStart(['block' => true]) ?>
<?= '
  $("#screenings-0-location-id").on("change", function() {
    alert("blaaaah");
  });
' ?>
<?php $this->Html->scriptEnd() ?>

<?= $this->Form->create($film) ?>

  <?= $this->Form->hidden('id') ?>
  <?= $this->Form->input('title', ['label' => 'Original Title']) ?>
  <?= $this->Form->input('translation.title', ['label' => 'Translation']) ?>
  
  <?= $this->Form->label('year', 'Released') ?>
  <?= $this->Form->year('year', ['dateFormat' => 'Y', 'minYear' => 1900, 'maxYear' => date('Y'), 'default' => date('Y'), 'empty' => false]) ?>
  
  <?= $this->Form->input('screenings.0.screened', ['label' => 'Screened']) ?>
  <?= $this->Form->input('screenings.0.location_id', ['label' => 'At', 'options' => $locations, 'empty' => '--']) ?>
  <?= $this->Form->input('screenings.0.viewers._ids', ['label' => 'With', 'options' => $viewers, 'multiple' => 'checkbox']) ?>

  <?= $this->Form->submit('Save', ['class' => 'btn']) ?>
<?= $this->Form->end() ?>