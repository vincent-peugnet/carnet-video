<?php
    $title = "tag $tag";
    $this->layout('layout', ['stylesheet' => 'tag.css', 'title' => $title]);
?>

<?php $this->start('main') ?>
    <h1>
        <a href="/tag/">tag</a>
        >
        <span><?= $tag ?></span>
    </h1>
    <ul class="clips">
        <?php foreach ($clips as $clip) : ?>
            <li>
                <?php $this->insert('clipThumbnail', ['id' => $clip['id']]) ?>
            </li>
        <?php endforeach ?>
    </ul>
<?php $this->stop() ?>


        