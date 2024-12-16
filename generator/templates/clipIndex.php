<?php
    $title = "index";
    $this->layout('layout', ['title' => $title]);
?>

<?php $this->start('main') ?>
<ul class="clips">
    <?php foreach ($clips as $clip) : ?>
        <li>
            <?php $this->insert('clipThumbnail', ['id' => $clip->id]) ?>
        </li>
        <?php endforeach ?>
    </ul>
<?php $this->stop() ?>


        