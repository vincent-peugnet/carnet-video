<?php
    $title = 'tags';
    $this->layout('general', ['title' => $title, 'currentPath' => '/tag/']);
?>

<?php $this->start('main') ?>
<h1>
    Tags
</h1>
<ul class="index">
    <?php foreach ($tags as $tag) : ?>
        <li>
            <a href="<?= $basePath ?>/tag/<?= $tag ?>/"><?= $tag ?></a>
        </li>
        <?php endforeach ?>
    </ul>
<?php $this->stop() ?>


        