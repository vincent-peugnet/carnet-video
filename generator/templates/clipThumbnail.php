<a href="/clip/<?= $clip->id ?>/" style="min-height: <?= floor(298 / ($clip->aspectRatio ?? 3)) ?>px; background-color: <?= $clip->color ?? 'grey' ?>">
    <img src="/assets/thumbnail/<?= $clip->id ?>.webp" class="foreground" alt="" loading="lazy">
    <img src="/assets/preview/<?= $clip->id ?>.webp" class="background" alt="" loading="lazy">
    <h2>#<?= $clip->id ?></h2>
</a>
