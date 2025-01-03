<?php
    $title = "AR $aspectRatio->ratio";
    $this->layout('general', ['title' => $title]);
?>

<?php $this->start('main') ?>
    <h1>
        <a href="<?= $basePath ?>/ar/">Formats d'image</a>
        >
        <span><?= $aspectRatio->ratio ?></span>
    </h1>
    <ul class="clips">
        <?php foreach ($clips as $clip) : ?>
            <li>
                <?php $this->insert('clipThumbnail', ['clip' => $clip]) ?>
            </li>
        <?php endforeach ?>
    </ul>
<?php $this->stop() ?>


        