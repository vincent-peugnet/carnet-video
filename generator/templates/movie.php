<?php
    $title = $movie->title;
    $this->layout('general', ['title' => $title]);
?>

<?php $this->start('main') ?>
    <h1>
        <a href="/movie/">Films</a>
        >
        <span><?= $movie->title ?></span>
    </h1>

    <div class="infos">
        <div>
            <span class="label">titre original</span>
            <em><?= $movie->originalTitle ?></em>
        </div>
        <?php if (!empty($movie->directors)) : ?>
            <div>
                <span class="label">réalisé par</span>
                <em><?= $movie->directors[0] ?></em><?php foreach (array_slice($movie->directors, 1) as $director) : ?>,
                <em><?= $director ?></em><?php endforeach ?>
            </div>
        <?php endif ?>
        <?php if ($movie->dop !== null) : ?>
            <div>
                <span class="label">image de</span>
                <em>
                    <?= $movie->dop ?>
                </em>
            </div>        
        <?php endif ?>
        <div>
            <span class="label">sorti en</span> <?= $movie->year ?>
        </div>
        <div class="extDb">
            <?php if ($movie->wiki !== null) : ?>
                <a href="<?= $movie->wiki ?>" class="wikipedia">Wikipédia</a>
            <?php endif ?>
            <?php if ($movie->tmdb !== null) : ?>
                <a href="https://www.themoviedb.org/movie/<?= $movie->tmdb ?>" class="tmdb">TMDB</a>
            <?php endif ?>
            <a href="https://www.imdb.com/title/<?= $movie->imdb ?>/" class="imdb">IMDb</a>
        </div>
    </div>



    <h2>Extraits</h2>
    <ul class="clips">
        <?php foreach ($clips as $clip) : ?>
            <li>
                <?php $this->insert('clipThumbnail', ['clip' => $clip]) ?>
            </li>
        <?php endforeach ?>
    </ul>
<?php $this->stop() ?>
