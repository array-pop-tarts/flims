<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->css('template') ?>
    <?= $this->Html->css('jquery-ui.min') ?>
    <?= $this->Html->css('jasny-bootstrap.min') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
</head>
<body>
  <div class="container">

    <div id="content">
      <div class="row">
        <div class="col-xs-24">
          <?= $this->Flash->render() ?>
          <?= $this->fetch('content') ?>
        </div>
      </div>
    </div>
    <footer>
    </footer>
  </div>

  <?= $this->Html->script('jquery-1.11.3.min') ?>
  <?= $this->Html->script('jquery-ui.min') ?>
  <?= $this->Html->script('jasny-bootstrap.min') ?>
  <?= $this->fetch('script') ?>
    
</body>
</html>
