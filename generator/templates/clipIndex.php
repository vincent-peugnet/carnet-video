<?php
    $title = 'extraits';
    $this->layout('general', ['title' => $title, 'currentPath' => '/clip/', 'scripts']);
?>

<?php $this->start('main') ?>

    <h1>
        Extraits
    </h1>
    <?php $this->insert('clipList', ['clips' => $clips]) ?>

<?php $this->stop() ?>
