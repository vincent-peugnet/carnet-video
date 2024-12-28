<?php
    $title = $movie->title;
    $this->layout('layout', ['title' => $title]);
?>

<?php $this->start('main') ?>
    <h1>
        <a href="/movie/">movie</a>
        >
        <span><?= $movie->title ?></span>
    </h1>

    <h2>Infos</h2>
    <?php if (!empty($movie->directors)) : ?>
        <div>
            <span>directed by</span>
            <em><?= $movie->directors[0] ?></em><?php foreach (array_slice($movie->directors, 1) as $director) : ?>,
            <em><?= $director ?></em><?php endforeach ?>
        </div>
    <?php endif ?>
    <?php if ($movie->dop !== null) : ?>
        <div>
            <span>photography by</span>
            <em>
                <?= $movie->dop ?>
            </em>
        </div>        
    <?php endif ?>
    <div>year: <?= $movie->year ?></div>
    <?php if ($movie->wiki !== null) : ?>
        <a href="<?= $movie->wiki ?>">Wikipédia</a>
    <?php endif ?>
    <h2>Clips</h2>
    <ul class="clips">
        <?php foreach ($clips as $clip) : ?>
            <li>
                <?php $this->insert('clipThumbnail', ['clip' => $clip]) ?>
            </li>
        <?php endforeach ?>
    </ul>
<?php $this->stop() ?>
