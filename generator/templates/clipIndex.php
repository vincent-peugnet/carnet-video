<?php
    $title = 'extraits';
    $this->layout('general', ['title' => $title, 'currentPath' => '/clip/', 'scripts']);
?>

<?php $this->start('main') ?>

    <h1>
        Extraits
    </h1>
    <details>
        <summary>⇟ filtrer</summary>
        <div id="filterForm">
            <?php foreach ($tags as $tag) : ?>
                <div>
                    <input type="checkbox" value="<?= $tag ?>" id="tag-<?= $tag ?>" class="tag">
                    <label for="tag-<?= $tag ?>"><?= $tag ?></label>
                </div>
            <?php endforeach ?>
        </div>
    </details>
    <?php $this->insert('clipList', ['clips' => $clips]) ?>

<?php $this->stop() ?>
