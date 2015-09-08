<?php $this->Html->scriptStart(['block' => true]) ?>
<?= '
  $("tbody.rowlink").rowlink()
' ?>
<?php $this->Html->scriptEnd() ?>

<div class="row">
  <div class="col-xs-12">
    <h1>Films (<?= $count ?>)</h1>
  </div>
  <div class="col-xs-12">
    <?= $this->Html->link('Add Film', ['action' => 'add'], ['class' => 'btn btn-primary']) ?>
  </div>
</div>

<table class="table">
  
  <?php
    $headers = [
      ['' => ['width' => '5%']],
      [$this->Paginator->sort('title', 'Film') => ['width' => '50%']],
      [$this->Paginator->sort('released') => ['class' => 'text-center', 'width' => '10%']],
      [$this->Paginator->sort('Screenings.screened', 'Watched') => ['width' => '35%']],
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
          $screenings
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