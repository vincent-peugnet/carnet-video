<head>
    <title><?= $title ?></title>
    <meta name="viewport" content="width=device-width" />
    <link rel="icon" type="image/png" href="<?= $basePath ?>/assets/favicon.png">
    <link rel="stylesheet" href="<?= $basePath ?>/assets/base.css">
    <?php if ($stylesheet !== null) : ?>
        <link rel="stylesheet" href="<?= $basePath ?>/assets/<?= $stylesheet ?>">
    <?php endif ?>
    <script src="<?= $basePath ?>/assets/tags.js" defer></script>
    <script src="<?= $basePath ?>/assets/infos.js" defer></script>
    <script src="<?= $basePath ?>/assets/script.js" defer></script>
</head>
