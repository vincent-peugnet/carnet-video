<?php
    $title = "AR $aspectRatio->ratio";
    $this->layout('layout', ['title' => $title]);
?>

<?php $this->start('main') ?>
    <h1>
        <a href="/ar/">ar</a>
        >
        <span><?= $aspectRatio->ratio ?></span>
    </h1>
    <ul class="clips">
        <?php foreach ($clips as $clip) : ?>
            <li>
                <?php $this->insert('clipThumbnail', ['id' => $clip['id']]) ?>
            </li>
        <?php endforeach ?>
    </ul>
<?php $this->stop() ?>


        