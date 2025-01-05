<?php
    $title = "collection $collection";
    $this->layout('general', ['title' => $title]);
?>

<?php $this->start('main') ?>
    <h1>
        <a href="<?= $basePath ?>/collection/">Collections</a>
        >
        <span><?= $collection ?></span>
    </h1>
    <?php $this->insert('clipList', ['clips' => $clips]) ?>
<?php $this->stop() ?>


        