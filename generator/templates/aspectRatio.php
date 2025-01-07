<?php
    $title = "format $aspectRatio->ratio";
    $this->layout('general', ['title' => $title]);
?>

<?php $this->start('main') ?>
    <h1>
        <a href="<?= $basePath ?>/ar/">Formats d'image</a>
        >
        <span><?= $aspectRatio->ratio ?></span>
    </h1>
    <?php $this->insert('clipList', ['clips' => $clips]) ?>
<?php $this->stop() ?>


        