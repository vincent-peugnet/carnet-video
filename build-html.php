#!/usr/bin/env php
<?php

require('./vendor/autoload.php');
$templates = new League\Plates\Engine('generator/templates');

print "🚀 \033[1mbuilding HTML\033[0m";

// COLLECTIONS

/** @var array[] $collections Associative array where key is collection string ID of collection and value is list of clips ID */
$collections = [];
$paths = glob('src/collections/*');
foreach ($paths as $path) {
    $id = basename($path);
    $collections[$id] = array_unique(file("src/collections/$id", FILE_IGNORE_NEW_LINES));
}

/** @var array[] $collectionsIndex associative array where key is clip int ID and value is list of collections string IDs */
$collectionsIndex = [];
foreach ($collections as $collection => $clips) {
    foreach ($clips as $id => $value) {
        $collectionsIndex[$id][] = $collection;
    }
}


// TAGS

/** @var string[] $tags Associative array where key is string ID and value is exactly the same */
$tags = file('src/allowedTags', FILE_IGNORE_NEW_LINES);
$tags = array_combine($tags, $tags);


// MOVIES

/** @var array[] $movies Associative array where key is movie int ID and value is an array containting metadata  */
$movies = [];
$paths = glob('src/movies/*.json');
foreach ($paths as $path) {
    $id = intval(basename($path, '.json'));
    $movie = json_decode(file_get_contents($path), true);
    $movie['id'] = $id;
    $movies[$id] = $movie;
}



// CLIPS

/** @var array[] $clips Associative array where key is clip int ID and value is an array containting metadata  */
$clips = [];
$paths = glob('src/clips/*.json');
foreach ($paths as $path) {
    $id = intval(basename($path, '.json'));
    $clip = json_decode(file_get_contents($path), true);
    $clip['id'] = $id;
    $clips[$id] = $clip;
}







// GENERATOR _____________________________________________________

function buildClips(array $clips, array $collectionsIndex, array $movies) : void
{
    global $templates;
    foreach ($clips as $id => $clip) {
        $collections = isset($collectionsIndex[$id]) ? $collectionsIndex[$id] : [];
        $movie = isset($clip['movie']) && isset($movies[$clip['movie']]) ? $movies[$clip['movie']] : null;
        $html = $templates->render('clip', ['id' => $id, 'clip' => $clip, 'collections' => $collections, 'movie' => $movie]);
        if (!is_dir("build/clip/$id")) {
            mkdir("build/clip/$id", 0777, true);
        }
        file_put_contents("build/clip/$id/index.html", $html);
    }

    $html = $templates->render('clipIndex', ['clips' => $clips]);
    file_put_contents("build/clip/index.html", $html);
}



function buildTags(array $tags, array $clips): void
{
    global $templates;
    foreach ($tags as $tag) {
        $filteredClips = [];
        foreach ($clips as $id => $clip) {
            if (in_array($tag, $clip['tags'])) {
                $filteredClips[] = $clip;
            }
        }
        $html = $templates->render('tag', ['tag' => $tag, 'clips' => $filteredClips]);
        if (!is_dir("build/tag/$tag")) {
            mkdir("build/tag/$tag");
        }
        file_put_contents("build/tag/$tag/index.html", $html);
    }
    
    $html = $templates->render('tagIndex', ['tags' => $tags]);
    file_put_contents("build/tag/index.html", $html);
}


function buildCollections(array $collections): void
{
    foreach ($collections as $collection => $clips) {
        global $templates;
        $html = $templates->render('collection', ['collection' => $collection, 'clips' => $clips]);
        if (!is_dir("build/collection/$collection")) {
            mkdir("build/collection/$collection", 0777, true);
        }
        file_put_contents("build/collection/$collection/index.html", $html);
    }

    $html = $templates->render('collectionIndex', ['collections' => $collections]);
    file_put_contents("build/collection/index.html", $html);
}


function buildMovies(array $movies, array $clips) : void
{
    global $templates;
    foreach ($movies as $id => $movie) {
        $filteredClips = array_filter($clips, function ($clip) use ($id): bool {
            return isset($clip['movie']) && $clip['movie'] === $id;
        });
        $html = $templates->render('movie', ['movie' => $movie, 'clips' => $filteredClips]);
        if (!is_dir("build/movie/$id")) {
            mkdir("build/movie/$id", 0777, true);
        }
        file_put_contents("build/movie/$id/index.html", $html);
    }    
    $html = $templates->render('movieIndex', ['movies' => $movies]);
    file_put_contents("build/movie/index.html", $html);
}


buildClips($clips, $collectionsIndex, $movies);
print '.';
buildTags($tags, $clips);
print '.';
buildCollections($collections);
print '.';
buildMovies($movies, $clips);
print '.';


$html = $templates->render('home');
file_put_contents("build/index.html", $html);
print '.';


if (!is_dir("build/assets")) {
    mkdir("build/assets");
}
copy('generator/style/base.css', 'build/assets/base.css');
print '.';


print "done!\n";
