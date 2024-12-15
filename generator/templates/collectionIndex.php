<?php
    $title = "collection index";
    $this->layout('layout', ['stylesheet' => 'index.css', 'title' => $title]);
?>

<?php $this->start('main') ?>
<ul>
    <?php foreach ($collections as $collection => $clips) : ?>
        <li>
            <a href="/collection/<?= $collection ?>/"><?= $collection ?></a>
        </li>
        <?php endforeach ?>
    </ul>
<?php $this->stop() ?>


        