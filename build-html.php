#!/usr/bin/env php
<?php

use Carnet\AspectRatio;
use Carnet\Clip;
use League\Plates\Engine;

/**
 * Delete folder and all it's content
 */
function delTree(string $dir)
{
    if (is_dir($dir)) {   
        $files = array_diff(scandir($dir), array('.', '..'));
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
        }
        return rmdir($dir);
    }
}

require('./vendor/autoload.php');
$templates = new Engine('generator/templates');
$templates->addData(['stylesheet' => null]);


/**
 * COLLECTIONS
 *
 * @return array[] Associative array where key is collection string ID of collection and value is list of clips ID
 **/
function getCollections() : array
{
    $collections = [];
    $paths = glob('src/collections/*');
    foreach ($paths as $path) {
        $id = basename($path);
        $collectionClipIds = array_unique(file("src/collections/$id", FILE_IGNORE_NEW_LINES));
        $collectionClipIds = array_map(function($id) : int {
            return intval($id);
        }, $collectionClipIds);
        $collections[$id] = $collectionClipIds;
    }
    ksort($collections);
    return $collections;
}

/**
 * @param array[] $collections
 * @return array[] associative array where key is clip int ID and value is list of collections string IDs
 **/
function getCollectionsIdIndex(array $collections) : array
{
    $collectionsIndex = [];
    foreach ($collections as $collection => $clips) {
        foreach ($clips as $id) {
            $collectionsIndex[$id][] = $collection;
        }
    }
    return $collectionsIndex;
}


/**
 * TAGS
 *
 * @return string[] Associative array where key is string ID and value is exactly the same
 **/
function getTags() : array
{
    $tags = file('src/tags', FILE_IGNORE_NEW_LINES);
    $tags = array_combine($tags, $tags);
    ksort($tags);
    return $tags;
}

/**
 * ASPECT RATIO
 *
 * @return AspectRatio[]
 **/
function getAspectRatios() : array
{
    $ratios = file('src/aspectRatios', FILE_IGNORE_NEW_LINES);
    $ratios = array_map(function($ratio) : float {
        return floatval($ratio);
    }, $ratios);
    sort($ratios);
    
    foreach ($ratios as $i => $ratio) {
        $prev = isset($ratios[$i - 1]) ? $ratios[$i - 1] : 0;
        $next = isset($ratios[$i + 1]) ? $ratios[$i + 1] : 10;
        $aspectRatios[] = new AspectRatio($ratio, $prev, $next);
    }
    return $aspectRatios;
}


/**
 * MOVIES
 *
 * @return array[] Associative array where key is movie int ID and value is an array containting metadata 
 **/
function getMovies() : array
{
    $movies = [];
    $paths = glob('src/movies/*.json');
    foreach ($paths as $path) {
        $id = intval(basename($path, '.json'));
        $movie = json_decode(file_get_contents($path), true);
        $movie['id'] = $id;
        $movies[$id] = $movie;
    }
    ksort($movies);
    return $movies;
}

/**
 * CLIPS
 *
 * @return Clip[] Associative array where key is clip int ID and value is an array containting metadata 
 **/
function getClips() : array
{
    $clips = [];
    $paths = glob('src/clips/*.json');
    foreach ($paths as $path) {
        $id = intval(basename($path, '.json'));
        $json = json_decode(file_get_contents($path), true);
        $clips[$id] = new Clip($id, $json);
    }
    ksort($clips);
    return $clips;
}

function removeHtmlBuild(): void
{
    delTree('build/clip');
    delTree('build/collection');
    delTree('build/movie');
    delTree('build/tag');
    delTree('build/ar');
}

/**
 * @param AspectRatio[] $aspectRatios
 * @param Clip[] $clips
 */
function buildClips(array $clips, array $collectionsIndex, array $movies, array $aspectRatios): void
{
    global $templates;
    mkdir('build/clip');
    foreach ($clips as $id => $clip) {
        $collections = isset($collectionsIndex[$id]) ? $collectionsIndex[$id] : [];
        $movie = $clip->movie !== null && isset($movies[$clip->movie]) ? $movies[$clip->movie] : null;
        if (($clip->aspectRatio) !== null) {
            foreach ($aspectRatios as $aspectRatio) {
                if ($aspectRatio->isInRange($clip->aspectRatio)) {
                    $matchedAspectRatio = $aspectRatio;
                }
            }
        } else {
            $matchedAspectRatio = null;
        }
        $html = $templates->render('clip', ['clip' => $clip, 'collections' => $collections, 'movie' => $movie, 'aspectRatio' => $matchedAspectRatio]);
        if (!is_dir("build/clip/$id")) {
            mkdir("build/clip/$id", 0777, true);
        }
        file_put_contents("build/clip/$id/index.html", $html);
    }

    $html = $templates->render('clipIndex', ['clips' => $clips]);
    file_put_contents("build/clip/index.html", $html);
}


/**
 * @param Clip[] $clips
 */
function buildTags(array $tags, array $clips): void
{
    global $templates;
    mkdir('build/tag');
    foreach ($tags as $tag) {
        $filteredClips = [];
        foreach ($clips as $clip) {
            if (in_array($tag, $clip->tags)) {
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

/**
 * @param AspectRatio[] $aspectRatios
 * @param Clip[] $clips
 */
function buildAspectRatios(array $aspectRatios, array $clips) : void
{
    global $templates;
    mkdir('build/ar');
    $filteredAspectRatios = [];
    foreach ($aspectRatios as $aspectRatio) {
        $filteredClips = [];
        foreach ($clips as $id => $clip) {
            if ($clip->aspectRatio === null) {
                unset($clips[$id]);
                continue;                
            }
            if ($aspectRatio->isInRange($clip->aspectRatio)) {
                $filteredClips[] = $clip;
                unset($clips[$id]);
            }
        }
        if (empty($filteredClips)) {
            continue;
        }
        $html = $templates->render('aspectRatio', ['aspectRatio' => $aspectRatio, 'clips' => $filteredClips]);
        $filteredAspectRatios[] = $aspectRatio;
        if (!is_dir("build/ar/$aspectRatio->slug")) {
            mkdir("build/ar/$aspectRatio->slug");
        }
        file_put_contents("build/ar/$aspectRatio->slug/index.html", $html);
    }
    $html = $templates->render('aspectRatioIndex', ['aspectRatios' => $filteredAspectRatios]);
    file_put_contents("build/ar/index.html", $html);
}

/**
 * @param Clip[] $clips
 */
function buildCollections(array $collections, array $clips): void
{
    global $templates;
    mkdir('build/collection');
    foreach ($collections as $collection => $collectionClipIds) {
        $collectionClipIds = array_flip($collectionClipIds);
        $collectionClips = array_intersect_key($clips, $collectionClipIds);
        $html = $templates->render('collection', ['collection' => $collection, 'clips' => $collectionClips]);
        if (!is_dir("build/collection/$collection")) {
            mkdir("build/collection/$collection", 0777, true);
        }
        file_put_contents("build/collection/$collection/index.html", $html);
    }

    $html = $templates->render('collectionIndex', ['collections' => $collections]);
    file_put_contents("build/collection/index.html", $html);
}

/**
 * @param Clip[] $clips
 */
function buildMovies(array $movies, array $clips): void
{
    global $templates;
    mkdir('build/movie');
    foreach ($movies as $id => $movie) {
        $filteredClips = array_filter($clips, function (Clip $clip) use ($id): bool {
            return isset($clip->movie) && $clip->movie === $id;
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

$clips = getClips();
$collections = getCollections();
$collectionsIndex = getCollectionsIdIndex($collections);
$movies = getMovies();
$aspectRatios = getAspectRatios();
$tags = getTags();

print "🚀 \033[1mbuilding HTML\033[0m";

removeHtmlBuild();
print '.';
buildClips($clips, $collectionsIndex, $movies, $aspectRatios);
print '.';
buildTags($tags, $clips);
print '.';
buildCollections($collections, $clips);
print '.';
buildMovies($movies, $clips);
print '.';
buildAspectRatios($aspectRatios, $clips);
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
