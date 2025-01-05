<header>
    <h1>
        <a href="<?= $basePath ?>/">Carnet Vidéo</a>
    </h1>
    <nav>
        <a class="<?= $currentPath === '/clip/' ? 'current' : '' ?>" href="<?= $basePath ?>/clip/">extraits</a>
        <a class="<?= $currentPath === '/tag/' ? 'current' : '' ?>" href="<?= $basePath ?>/tag/">tags</a>
        <a class="<?= $currentPath === '/movie/' ? 'current' : '' ?>" href="<?= $basePath ?>/movie/">films</a>
        <a class="<?= $currentPath === '/ar/' ? 'current' : '' ?>" href="<?= $basePath ?>/ar/">formats</a>
        <a class="<?= $currentPath === '/collection/' ? 'current' : '' ?>" href="<?= $basePath ?>/collection/">collections</a>
    </nav>
</header>
