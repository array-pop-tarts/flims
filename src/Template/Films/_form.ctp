<?php $this->Html->scriptStart(['block' => true]) ?>
<?= '

  // ------ DATEPICKER ------
  
  $(".datepicker").datepicker({
    changeMonth: true,
    changeYear: true,
    dateFormat: "yy-mm-dd"
  });
  
  // ------ ADD SCREENING ROW ------
  
  $("#add-screening").on("click", function(e) {
    e.preventDefault();
    
    var lastFieldset = $(".screening-fieldset").last();
    var fieldsetIdParts = lastFieldset.attr("id").split("-");
    var itemId = fieldsetIdParts[fieldsetIdParts.length - 1];
    
    var newFieldset = lastFieldset.clone().insertAfter(lastFieldset).hide().slideDown();
    newFieldset.addNumberToNewRow("id", "fieldset-", itemId);
    
    var key = newFieldset.find(".key");
    var keyVal = key.html();
    key.html(parseInt(keyVal) + 1);
    
    newFieldset.find("input").each(function() {
      var $this = $(this);
      
      if ($this.attr("type") != "checkbox")
        $this.val("");
      
      if ($this.attr("id")) {
        if ($this.attr("id") == "screenings-" + itemId + "-new-location")
          $this.attr("readonly", true);
        
        $this.addNumberToNewRow("id", "screenings-", itemId);
      }
      
      $this.addNumberToNewRow("name", "screenings[", itemId);
    });
    newFieldset.find("select").each(function() {
      var $this = $(this);
      $this.find("option").removeAttr("selected");
      $this.addNumberToNewRow("id", "screenings-", itemId);
      $this.addNumberToNewRow("name", "screenings[", itemId);
    });
    newFieldset.find("[type=checkbox]").each(function() {
      $(this).attr("checked", false);
    });
    newFieldset.find(".checkbox").children("label").each(function() {
      var $this = $(this);
      $this.addNumberToNewRow("for", "screenings-", itemId);
    });
    
    newFieldset.find(".new-viewers").hide().addNumberToNewRow("id", "new-viewers-", itemId);
    
    newFieldset.find(".datepicker").removeClass("hasDatepicker").datepicker({
      changeMonth: true,
      changeYear: true,
      dateFormat: "yy-mm-dd"
    });
    
    newFieldset.find("#remove-screening-" + itemId).addNumberToNewRow("id", "remove-screening-", itemId);
    $(".remove-screening").removeClass("disabled");
  });
  
  // ------ REMOVE SCREENING ROW ------
  
  $("form").on("click", ".remove-screening", function(e) {
    e.preventDefault();
    
    var idParts = $(this).attr("id").split("-");
    var itemId = idParts[idParts.length - 1];
    
    $("#fieldset-" + itemId).slideUp(function() {
      $(this).remove();
      
      var fieldsetRemoveButtons = $(".remove-screening");
      if (fieldsetRemoveButtons.length < 2)
        fieldsetRemoveButtons.addClass("disabled");
      
      $(".key").each(function( index ) {
        $(this).html((index + 1));
      });
      
    });
    
  });
  
  // ------ NEW LOCATION ------
  
  $("form").on("change", ".location-select", function() {
    
    $this = $(this);
    var idParts = $this.attr("id").split("-");
    var itemId = idParts[1];
  
    if ($this.val() == "new_location")
      $("#screenings-" + itemId + "-new-location").attr("readonly", false);
    else
      $("#screenings-" + itemId + "-new-location").attr("readonly", true).val("");
    
  });
  
  // ------ NEW VIEWER ------
  
  $("form").on("change", ".viewers input", function() {
    $this = $(this);
    if ($this.val() == "new_viewers") {
      var idParts = $this.attr("id").split("-");
      var itemId = idParts[1];
      $("#new-viewers-" + itemId).slideDown();
    }
  });
  
  // ------ NEW ROW FUNCTIONS ------
  
  $.fn.addNumberToNewRow = function(attribute, stringPart, lastId) {
    this.attr(attribute, this.attr(attribute).replace(stringPart + lastId, stringPart + (parseInt(lastId) + 1)));
  }
  
  
' ?>
<?php $this->Html->scriptEnd() ?>

<?= $this->Form->create($film) ?>
  
  <fieldset>
    <legend><h1>Add a Film</h1></legend>
    <?= $this->Form->hidden('id') ?>
    
    <div class="row">
      <div class="col-xs-22">
        <?= $this->Form->input('title', ['placeholder' => 'Original Title', 'label' => false,'class' => 'input-lg']) ?>
      </div>
      <div class="col-xs-2">
        <?php
          if ($film->id) {
            $deleteLink = ['action' => 'delete', $film->id];
            $disabledDeleteClass = '';
          }
          else {
            $deleteLink = ['#'];
            $disabledDeleteClass = 'disabled';
          }
        ?>
        <?= $this->Html->link(
          $this->Html->icon('times'),
          $deleteLink,
          [
            'class' => "btn btn-warning btn-lg pull-right $disabledDeleteClass",
            'escape' => false,
            'title' => 'Delete',
            'confirm' => 'Delete "' . $film->title . '"?'
          ]
        ) ?>
      </div>
    </div>
    
    <div class="row">
      <div class="col-xs-14">
        <?= $this->Form->input('translation.title', ['placeholder' => 'Translation', 'label' => false]) ?>
      </div>
      <div class="col-xs-4">
        <?= $this->Form->input('released', ['options' => $years, 'default' => date('Y'), 'empty' => false, 'label' => false]) ?>
      </div>
      <div class="col-xs-6">
        <?= $this->Form->input('media._ids', ['multiple' => 'checkbox', 'options' => $media, 'label' => false]) ?>
      </div>
    </div>
    
    <?php foreach($film->screenings as $sKey => $screening): ?>
      <fieldset id="fieldset-<?= $sKey ?>" class="screening-fieldset">
        <div class="row">
          <div class="col-xs-1">
            <label class="key"><?= $sKey + 1 ?></label>
            <?= $this->Form->input("screenings.$sKey.id") ?>
          </div>
          <div class="col-xs-5">
            <?= $this->Form->input("screenings.$sKey.screened", [
              'label' => false,
              'type' => 'text',
              'dateFormat' => 'Ymd',
              'class' => 'datepicker'
            ]) ?>
          </div>
          <div class="col-xs-8">
            <?= $this->Form->input("screenings.$sKey.location_id", [
              'label' => false,
              'options' => $locations,
              'empty' => '-- Venue --',
              'class' => 'location-select'
            ]) ?>
          </div>
          <div class="col-xs-8">
            <?= $this->Form->input("screenings.$sKey.new_location", ['label' => false, 'placeholder' => 'New Venue', 'readonly' => true]) ?>
          </div>
          <div class="col-xs-2">
            <?php $disabledClass = (count($film->screenings) < 2) ? 'disabled' : '' ?>
            <?= $this->Html->link(
              $this->Html->icon('minus'),
              ['#'],
              [
                'class' => "btn btn-success remove-screening $disabledClass pull-right",
                'id' => "remove-screening-$sKey",
                'escape' => false
              ]
            ) ?>
          </div>
        </div>
        <div class="viewers">
          <?= $this->Form->input("screenings.$sKey.viewers._ids", ['label' => false, 'options' => $viewers, 'multiple' => 'checkbox']) ?>
        </div>
        <div class="row new-viewers" id="new-viewers-<?= $sKey ?>" style="display: none">
          <div class="col-xs-offset-8 col-xs-8">
            <?= $this->Form->input("screenings.$sKey.new_viewers", ['label' => false, 'placeholder' => 'New Friends', 'class' => 'input-sm']) ?>
          </div>
        </div>
      </fieldset>
    <?php endforeach ?>
    <div class="pull-right">
      <?= $this->Html->link(
        $this->Html->icon('plus'),
        ['#'],
        ['class' => 'btn btn-info', 'id' => 'add-screening', 'escape' => false, 'title' => 'Saw it again']
      ) ?>
    </div>
    
  </fieldset>

  <div class="submit text-center">
    <?= $this->Form->submit('Save', ['class' => 'btn btn-primary btn-lg']) ?>
  </div>
  
<?= $this->Form->end() ?>