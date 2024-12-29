<?php
    $title = "tag index";
    $this->layout('general', ['title' => $title]);
?>

<?php $this->start('main') ?>
<h1>
    Tags
</h1>
<ul class="index">
    <?php foreach ($tags as $tag) : ?>
        <li>
            <a href="/tag/<?= $tag ?>/"><?= $tag ?></a>
        </li>
        <?php endforeach ?>
    </ul>
<?php $this->stop() ?>


        