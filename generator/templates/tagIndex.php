<?php
    $title = "tag index";
    $this->layout('layout', ['title' => $title]);
?>

<?php $this->start('main') ?>
<ul>
    <?php foreach ($tags as $tag) : ?>
        <li>
            <a href="/tag/<?= $tag ?>/"><?= $tag ?></a>
        </li>
        <?php endforeach ?>
    </ul>
<?php $this->stop() ?>


        