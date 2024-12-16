<html>
    <?=$this->insert('head', ['stylesheet' => $stylesheet, 'title' => $title])?>
    <body>
        <?=$this->insert('header')?>
        <main>
            <?=$this->section('main')?>
        </main>
        <div class="spacer"></div>
        <footer>
            <p>Rendered at <?= date("Y-m-d H:i:s") ?> with <a href="https://github.com/vincent-peugnet/carnet-video">*carnet-video*</a></p>
        </footer>
    </body>
</html>
