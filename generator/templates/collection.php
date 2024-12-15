<?php
    $title = "collection $collection";
    $this->layout('layout', ['title' => $title]);
?>

<?php $this->start('main') ?>
    <h1>
        <a href="/collection/">collection</a>
        >
        <span><?= $collection ?></span>
    </h1>
    <ul class="clips">
        <?php foreach ($clips as $clip) : ?>
            <li>
                <?php $this->insert('clipThumbnail', ['id' => $clip]) ?>
            </li>
        <?php endforeach ?>
    </ul>
<?php $this->stop() ?>


        