<?php
    $title = "ar index";
    $this->layout('general', ['title' => $title]);
?>

<?php $this->start('main') ?>
<h1>
    Formats d'image
</h1>
<ul class="index">
    <?php foreach ($aspectRatios as $aspectRatio) : ?>
        <li>
            <a href="/ar/<?= $aspectRatio->slug ?>/"><?= $aspectRatio->ratio ?></a>
        </li>
        <?php endforeach ?>
    </ul>
<?php $this->stop() ?>


        