<aside>
    <span class="info"><span id="clipCount"><?= count($clips) ?></span> extrait(s)</span>
    <button id="shuffleClips">⤭ mélanger</button>
</aside>
<ul class="clips">
    <?php foreach ($clips as $clip) : ?>
        <li id="<?= $clip->id ?>">
            <a href="<?= $basePath ?>/clip/<?= $clip->id ?>/" style="min-height: <?= floor(298 / ($clip->aspectRatio ?? 3)) ?>px; background-color: <?= $clip->color ?? 'grey' ?>;">
                <img src="<?= $basePath ?>/assets/thumbnail/<?= $clip->id ?>.webp" class="thumbnail" alt="clip id #<?= $clip->id ?>" loading="lazy">
                <img src="<?= $basePath ?>/assets/preview/<?= $clip->id ?>.webp" class="preview" alt="" loading="lazy">
                <h2>#<?= $clip->id ?></h2>
            </a>
        </li>
    <?php endforeach ?>
</ul>
