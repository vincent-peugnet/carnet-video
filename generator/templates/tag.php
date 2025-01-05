<?php
    $title = "tag $tag";
    $this->layout('general', ['title' => $title]);
?>

<?php $this->start('main') ?>
    <h1>
        <a href="<?= $basePath ?>/tag/">Tags</a>
        >
        <span><?= $tag ?></span>
    </h1>
    <?php $this->insert('clipList', ['clips' => $clips]) ?>
<?php $this->stop() ?>


        