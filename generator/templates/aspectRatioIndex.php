<?php
    $title = "ar index";
    $this->layout('layout', ['title' => $title]);
?>

<?php $this->start('main') ?>
<ul>
    <?php foreach ($aspectRatios as $aspectRatio) : ?>
        <li>
            <a href="/ar/<?= $aspectRatio->slug ?>/"><?= $aspectRatio->ratio ?></a>
        </li>
        <?php endforeach ?>
    </ul>
<?php $this->stop() ?>


        