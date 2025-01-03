<?php
    $title = 'Home';
    $this->layout('base', ['title' => $title]);
?>

<?php $this->start('body') ?>

<header>
    <h1>Carnet Vidéo de Vincent</h1>
</header>

<main class="home">

    <p>
        Ce site contient une collection d'extraits de films.
        Elle est collectée manuellement au fil du temps depuis 2025 par Vincent Peugnet.
    </p>

    <nav>
        <ul class="index">
            <li><a href="<?= $basePath ?>/clip/">tout les extraits</a></li>
            <li><a href="<?= $basePath ?>/tag/">tags</a></li>
            <li><a href="<?= $basePath ?>/collection/">collections</a></li>
            <li><a href="<?= $basePath ?>/movie/">films</a></li>
            <li><a href="<?= $basePath ?>/ar/">formats d'image</a></li>
        </ul>
    </nav>

    <h2>
        Plus d'infos
    </h2>

    <ul>
        <li>
            <a href="https://246.eu/carnet-video">Notes à propos du projet</a>
        </li>
        <li>
            <a href="https://github.com/vincent-peugnet/carnet-video">code source du projet sur Github</a>
        </li>
        <li>
            <a href="https://246.eu/vincent-peugnet">Vincent Peugnet</a>
        </li>
    </ul>

    <h2>
        Autre ressources
    </h2>

    <ul>
        <li>
            <strong><a href="https://www.youtube.com/user/everyframeapainting/videos">Every frame a Painting</a></strong>
            Série d'essais vidéo créés par Taylor Ramos et Tony Zhou.
            C'est super intéressant !!
        </li>
        <li>
            <strong><a href="https://eyecannndy.com/">Eye canndy</a></strong>
            Liste de vocabulaire d'effets visuels.
            Un peu plus orienté clip ou publicité.
            (Attention ça bouge de partout)
        </li>
    </ul>
</main>

<?php $this->stop() ?>


        