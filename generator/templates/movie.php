<?php
    $title = $movie['title'];
    $this->layout('layout', ['stylesheet' => 'tag.css', 'title' => $title]);
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
                <?php $this->insert('clipThumbnail', ['id' => $clip['id']]) ?>
            </li>
        <?php endforeach ?>
    </ul>
<?php $this->stop() ?>


        