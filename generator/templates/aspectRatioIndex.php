<?php
    $title = "ar index";
    $this->layout('general', ['title' => $title]);
?>

<?php $this->start('main') ?>
<h1>
    Formats d'image
</h1>
<ul>
    <?php foreach ($aspectRatios as $aspectRatio) : ?>
        <li>
            <a href="/ar/<?= $aspectRatio->slug ?>/" class="ar" style="width: 100px; height: <?= floor(100 / $aspectRatio->ratio) ?>px; line-height: <?= floor(100 / $aspectRatio->ratio) ?>px;"><?= $aspectRatio->ratio ?></a>
        </li>
    <?php endforeach ?>
</ul>
<?php $this->stop() ?>


        