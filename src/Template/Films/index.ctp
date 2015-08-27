<div class="row">
  <div class="col-xs-12">
    <h1>Films (<?= $count ?>)</h1>
  </div>
  <div class="col-xs-12">
    <?= $this->Html->link('Add Film', ['action' => 'add'], ['class' => 'btn']) ?>
  </div>
</div>

<table class="table">
  
  <?php
    $headers = [
      $this->Paginator->sort('title', 'Film'),
      $this->Paginator->sort('year', 'Released'),
      $this->Paginator->sort('Screenings.screened', 'Watched'),
      ''
    ]
  ?>
  
  <thead>
    <?= $this->Html->tableHeaders($headers) ?>
  </thead>
  
  <tbody>
    <?php $rows = []; ?>
    <?php foreach ($films as $film): ?>
      <?php
        $translation = ($film->translation) ? $film->translation->title : '';
        
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
          '<div>' . $film->title . '</div>
          <div>' . $translation . '</div>',
          $film->year,
          $screenings,
          $this->Html->link('Edit', ['action' => 'edit', $film->id])
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