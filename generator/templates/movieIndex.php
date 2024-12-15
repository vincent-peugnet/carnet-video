<?php
    $title = "movie index";
    $this->layout('layout', ['stylesheet' => 'index.css', 'title' => $title]);
?>

<?php $this->start('main') ?>
<ul>
    <?php foreach ($movies as $id => $movie) : ?>
        <li>
            <a href="/movie/<?= $id ?>/">
                <?= $movie['title'] ?>
                <span class="year"><?= $movie['year'] ?></span>
            </a>
        </li>
        <?php endforeach ?>
    </ul>
<?php $this->stop() ?>


        