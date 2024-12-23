<?php
    $title = $movie['title'];
    $this->layout('layout', ['title' => $title]);
?>

<?php $this->start('main') ?>
    <h1>
        <a href="/movie/">movie</a>
        >
        <span><?= $movie['title'] ?></span>
        <span class="year"><?= $movie['year'] ?></span>
    </h1>
    <a href="https://www.themoviedb.org/movie/<?= $movie['id'] ?>">TMDB</a>
    <ul class="clips">
        <?php foreach ($clips as $clip) : ?>
            <li>
                <?php $this->insert('clipThumbnail', ['clip' => $clip]) ?>
            </li>
        <?php endforeach ?>
    </ul>
<?php $this->stop() ?>


        