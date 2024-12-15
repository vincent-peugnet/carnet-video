<?php
    $title = "clip $id";
    $this->layout('layout', ['stylesheet' => 'clip.css', 'title' => $title]);
?>

<?php $this->start('main') ?>
    <h1>
        <a href="/clip/">clip</a>
        >
        <span># <?= $id ?></span>
    </h1>
    <video muted controls autoplay loop>
        <source src="/assets/clip/<?= $id ?>.webm" type="video/webm" />
    </video>
    <p><?= $clip['description'] ?></p>
    <div class="tags">
        <h2>Tags</h2>
        <?php foreach ($clip['tags'] as $tag) : ?>
            <a href="/tag/<?= $tag ?>/"><?= $tag ?></a>
        <?php endforeach ?>
    </div>
    <?php if ($movie !== null) : ?>
        <div class="movie">
            <h2>Movie</h2>
            <a href="/movie/<?= $movie['id'] ?>/">
                <?= $movie['title'] ?>
                <span class="year"><?= $movie['year'] ?></span>
            </a>
        </div>
    <?php endif ?>
    <div class="collections">
        <h2>Collections</h2>
        <ul>
            <?php foreach ($collections as $collection) : ?>
                <li>
                    <a href="/collection/<?= $collection ?>/"><?= $collection ?></a>
                </li>
            <?php endforeach ?>
        </ul>
    </div>
<?php $this->stop() ?>
