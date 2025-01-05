<head>
    <title>Carnet - <?= $title ?></title>
    <meta name="viewport" content="width=device-width" />
    <link rel="icon" type="image/png" href="/assets/favicon.png">
    <link rel="stylesheet" href="<?= $basePath ?>/assets/base.css">
    <?php if ($stylesheet !== null) : ?>
        <link rel="stylesheet" href="<?= $basePath ?>/assets/<?= $stylesheet ?>">
    <?php endif ?>
    <script src="<?= $basePath ?>/assets/script.js" async></script>
</head>
