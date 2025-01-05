<?php
    $title = "movie index";
    $this->layout('general', ['title' => $title, 'currentPath' => '/movie/']);
?>

<?php $this->start('main') ?>
<h1>
    Films
</h1>
<ul class="index">
    <?php foreach ($movies as $id => $movie) : ?>
        <li>
            <a href="<?= $basePath ?>/movie/<?= $id ?>/">
                <?= $movie->title ?>
                <span class="year"><?= $movie->year ?></span>
            </a>
        </li>
        <?php endforeach ?>
    </ul>
<?php $this->stop() ?>


        