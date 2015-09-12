<?php $this->Html->scriptStart(['block' => true]) ?>
<?= '
  $("tbody.rowlink").rowlink()
' ?>
<?php $this->Html->scriptEnd() ?>

<div class="header">
  <?= $this->Form->create(null, ['action' => 'index', 'type' => 'get', 'class' => 'inline-form']) ?>
    <div class="row">
      <div class="col-xs-15">
        <?= $this->Form->input('title', ['label' => false, 'class' => 'input-lg', 'placeholder' => 'What I see']) ?>
      </div>
      <div class="col-xs-3">
        <?= $this->Form->input('released', ['label' => false, 'class' => 'input-lg', 'options' => $years, 'empty' => 'Year']) ?>
      </div>
      <div class="col-xs-4">
        <?= $this->Form->input('media', ['label' => false, 'class' => 'input-lg', 'options' => $media, 'empty' => 'Media']) ?>
      </div>
      <div class="col-xs-2">
        <?= $this->Form->button($this->Html->icon('search'), ['type' => 'submit', 'class' => 'btn btn-primary btn-lg pull-right', 'escape' => false]) ?>
      </div>
    </div>
  <?= $this->Form->end() ?>
</div>

<table class="table">
  
  <?php
    $headers = [
      ['' => ['width' => '5%']],
      [$this->Paginator->sort('title', $this->Html->icon('film'), ['escape' => false]) => ['width' => '48%']],
      [$this->Paginator->sort('released', $this->Html->icon('star'), ['escape' => false]) => ['class' => 'text-center', 'width' => '10%']],
      [$this->Paginator->sort('Screenings.screened', 'Watched') => ['width' => '35%']],
      [
        $this->Html->link(
          $this->Html->icon('plus'),
          ['action' => 'add'],
          [
            'class' => 'btn btn-info btn-lg pull-right',
            'escape' => false,
            'title' => 'New Flim'
          ]
        ) => ['width' => '2%']
      ]
    ]
  ?>
  
  <thead>
    <?= $this->Html->tableHeaders($headers) ?>
  </thead>
  
  <tbody class="rowlink" data-link="row">
    <?php $rows = []; ?>
    <?php foreach ($films as $film): ?>
      <?php
        
        $media = [];
        foreach ($film->media as $medium) {
          $media[] = '<span class="btn btn-success">' . substr($medium->name, 0, 1) . '</span>';
        }
        $media = implode("", $media);
        
        $title = [];
        $title[] = $film->title;
        if ($film->translation)
          $title[] = '<em class="translation">' . $film->translation->title . '</em>';
        $title = implode("<br>", $title);
        
        $screenings = [];
        foreach ($film->screenings as $screening) {
          
          $screeningInfo = [$this->Time->format($screening->screened, 'Y')];
          
          if ($screening->location)
            $screeningInfo[] = $screening->location->name;
          
          if (! empty($screening->viewers)) {
            $viewers = [];
            foreach ($screening->viewers as $viewer) {
              $viewers[] = $viewer->name;
            }
            $screeningInfo[] = implode(", ", $viewers);
          }
          
          $screenings[] = implode(" &ndash; ", $screeningInfo);
        }
        $screenings = implode("<br>", $screenings);
        
        $row = [
          $media,
          $this->Html->link(
            $title,
            ['action' => 'edit', $film->id],
            ['escape' => false]
          ),
          [$film->released, ['class' => 'text-center']],
          [$screenings, ['colspan' => 2]]
        ];
        
        $rows[] = $row;
      ?>
    <?php endforeach ?>
    <?= $this->Html->tableCells($rows) ?>
  </tbody>
</table>
<div>
  <?= $this->Paginator->prev('<< Previous'); ?>
  <?= $this->Paginator->counter(
    'Page {{page}} of {{pages}}, showing {{current}} records out of
     {{count}} total, starting on record {{start}}, ending on {{end}}'
  ); ?>
  <?= $this->Paginator->next('Next >>'); ?>
</div>