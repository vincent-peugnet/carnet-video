<a href="/clip/<?= $clip->id ?>/" style="height: <?= floor(298 / $clip->aspectRatio) ?>px">
    <img src="/assets/thumbnail/<?= $clip->id ?>.webp" class="foreground" alt="" loading="lazy">
    <img src="/assets/thumbnail/<?= $clip->id ?>.gif" class="background" alt="" loading="lazy">
    <h2>#<?= $clip->id ?></h2>
</a>
