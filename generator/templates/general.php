<?php $this->layout('base', ['title' => $title]) ?>
<?php $this->start('body') ?>
    <?=$this->insert('header')?>
    <main>
        <?=$this->section('main')?>
    </main>
    <div class="spacer"></div>
    <footer>
        <p>généré le <?= date("Y-m-d") ?> à <?= date("H:i:s") ?></p>
    </footer>
<?php $this->stop() ?>
