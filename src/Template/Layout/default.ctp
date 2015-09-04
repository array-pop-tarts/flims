<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->css('template.css') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
</head>
<body>
    <header>
        <div class="header-title">
            <span><?= $this->fetch('title') ?></span>
        </div>
    </header>
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
    <?= $this->fetch('script') ?>
    
</body>
</html>
