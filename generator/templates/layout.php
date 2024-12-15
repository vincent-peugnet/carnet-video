<html>
    <?=$this->insert('head', ['stylesheet' => $stylesheet, 'title' => $title])?>
    <body>
        <?=$this->insert('header')?>
        <main>
            <?=$this->section('main')?>
        </main>
    </body>
</html>
