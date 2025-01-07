<?php
    $title = 'collections';
    $this->layout('general', ['title' => $title, 'currentPath' => '/collection/']);
?>

<?php $this->start('main') ?>
<h1>
    Collections
</h1>
<ul class="index">
    <?php foreach ($collections as $collection => $clips) : ?>
        <li>
            <a href="<?= $basePath ?>/collection/<?= $collection ?>/"><?= $collection ?></a>
        </li>
        <?php endforeach ?>
    </ul>
<?php $this->stop() ?>


        