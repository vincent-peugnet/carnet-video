<?php
    $title = "index";
    $this->layout('general', ['title' => $title]);
?>

<?php $this->start('main') ?>
<h1>
    Extraits
</h1>
<ul class="clips"><?php foreach ($clips as $clip) : ?><li><a href="<?= $basePath ?>/clip/<?= $clip->id ?>/" style="min-height: <?= floor(298 / ($clip->aspectRatio ?? 3)) ?>px; background-color: <?= $clip->color ?? 'grey' ?>;"><img src="<?= $basePath ?>/assets/thumbnail/<?= $clip->id ?>.webp" class="thumbnail" alt="clip id #<?= $clip->id ?>" loading="lazy"><img src="<?= $basePath ?>/assets/preview/<?= $clip->id ?>.webp" class="preview" alt="" loading="lazy"><h2>#<?= $clip->id ?></h2></a></li><?php endforeach ?></ul>
<?php $this->stop() ?>
