<?php
    $title = "extrait $clip->id";
    $this->layout('general', ['title' => $title]);
?>

<?php $this->start('main') ?>
    <h1>
        <a href="<?= $basePath ?>/clip/">Extraits</a>
        >
        <span># <?= $clip->id ?></span>
        <span class="float">
            <a
                <?php if ($prev !== null) : ?>
                    href="<?= $basePath ?>/clip/<?= $prev->id ?? '' ?>/"
                <?php else: ?>
                    class="inactive"
                <?php endif; ?>
            >◂</a>
            <a
                <?php if ($next !== null) : ?>
                    href="<?= $basePath ?>/clip/<?= $next->id ?? '' ?>/"
                <?php else: ?>
                    class="inactive"
                <?php endif; ?>
            >▸</a>
        </span>
    </h1>
    <video muted controls autoplay loop>
        <source src="<?= $basePath ?>/assets/clip/<?= $clip->id ?>.webm" type="video/webm" />
    </video>
    <p><?= $clip->description ?></p>

    <div class="tags">
        <h2>Tags</h2>
        <span>
            <?php foreach ($clip->tags as $tag) : ?>
                <a href="<?= $basePath ?>/tag/<?= $tag ?>/"><?= $tag ?></a>
            <?php endforeach ?>
            </span>
        <a class="button" href="<?= $basePath . '/clip/#' . urlencode(implode(' ', $clip->tags)) ?>">✻</a>
    </div>

    <?php if ($movie !== null) : ?>
        <div class="movie">
            <h2>Film</h2>
            <a href="<?= $basePath ?>/movie/<?= $movie->id ?>/">
                <?= $movie->title ?>
                <span class="year"><?= $movie->year ?></span>
            </a>
        </div>
    <?php endif ?>
    <?php if ($aspectRatio !== null) : ?>
        <div class="aspectRatio">
            <h2>Format d'image</h2>
            <a href="<?= $basePath ?>/ar/<?= $aspectRatio->slug ?>/">
                <?= $aspectRatio->ratio ?>
            </a>
        </div>
    <?php endif ?>
    <div class="collections">
        <h2>Collections</h2>
        <ul>
            <?php foreach ($collections as $collection) : ?>
                <li>
                    <a href="<?= $basePath ?>/collection/<?= $collection ?>/"><?= $collection ?></a>
                </li>
            <?php endforeach ?>
        </ul>
    </div>
<?php $this->stop() ?>
