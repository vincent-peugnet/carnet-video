<?php
    $title = 'carnet vidéo';
    $this->layout('base', ['title' => $title]);
?>

<?php $this->start('body') ?>

<header>
    <h1>Carnet Vidéo</h1>
</header>

<main class="home">

    <p>
        Bienvenue sur le carnet vidéo de Vincent Peugnet.
        ✦
        Ce site contient une petite collection d'extraits de films collectés au gré des visonnages.
    </p>

    <nav>
        <ul class="index">
            <li><a href="<?= $basePath ?>/clip/">tout les extraits</a></li>
            <li><a href="<?= $basePath ?>/tag/">tags</a></li>
            <li><a href="<?= $basePath ?>/movie/">films</a></li>
            <li><a href="<?= $basePath ?>/ar/">formats d'image</a></li>
            <li><a href="<?= $basePath ?>/collection/">collections</a></li>
        </ul>
    </nav>

    <h2>
        à propos
    </h2>
    <ul>
        <li>
            ⤻ <a href="https://246.eu/vincent-peugnet">Vincent Peugnet</a>
        </li>
        <li>
            ⤻ <a href="https://246.eu/carnet-video">notes à propos du projet</a>
        </li>
        <li>
            ⤻ <a href="https://github.com/vincent-peugnet/carnet-video">code source du projet sur Github</a>
        </li>

        <p>
            Hébergé par <strong><a href="https://club1.fr">club1</a> ❤</strong>
        </p>

        <p>
            (ça marche mieux sur Firefox à cause d'un bug de Chromium)
        </p>

        <p class="terms">
            Ce site Web ne détient aucun droit sur les différents matériaux visuels qui y sont présentés.
            Ils sont partagés ici dans un but pédagogique et non-lucratif.
            Ce site ne collecte aucune donnée personnelle.
        </p>
    </ul>

    <h2>
        d'autres ressources
    </h2>

    <ul>
        <li>
            <strong>⤤ <a href="https://www.youtube.com/user/everyframeapainting/videos">Every frame a Painting</a></strong>
            Série d'essais vidéo créés par <em>Taylor Ramos</em> et <em>Tony Zhou</em>.
            C'est super intéressant !!
        </li>
        <li>
            <strong>⤤ <a href="https://eyecannndy.com/">Eye canndy</a></strong>
            Liste de vocabulaire d'effets visuels.
            Un peu plus orienté clip ou publicité.
            (Attention ça bouge de partout)
        </li>
    </ul>
</main>

<?php $this->stop() ?>


        