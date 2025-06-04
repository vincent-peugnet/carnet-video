#!/usr/bin/env php
<?php

use Carnet\AspectRatio;
use Carnet\Clip;
use Carnet\Movie;
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

/**
 * @param int $current Current clip ID
 * @param Clip[] Associative array where key is clip int ID and value is a Clip object
 */
function getPreviousClip(int $current, array $clips): ?Clip
{
    for ($i=1; $i < 15; $i++) { 
        $id = $current - $i;
        if (isset($clips[$id])) {
            return $clips[$id];
        }
    }
    return null;
}

/**
 * @param int $current Current clip ID
 * @param Clip[] Associative array where key is clip int ID and value is a Clip object
 */
function getNextClip(int $current, array $clips): ?Clip
{
    for ($i=1; $i < 15; $i++) { 
        $id = $current + $i;
        if (isset($clips[$id])) {
            return $clips[$id];
        }
    }
    return null;
}

require('./vendor/autoload.php');

if (is_file('config/basePath')) {
    $basePath = trim(file_get_contents('config/basePath'), " \n\r\t\v\0/");
    $basePath = empty($basePath) ? '' : "/$basePath";
} else {
    $basePath = '';
}

$templates = new Engine('generator/templates');
$templates->addData(['stylesheet' => null, 'basePath' => $basePath]);


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
 * @return Movie[] Associative array where key is movie int ID and value is a Movie object 
 **/
function getMovies() : array
{
    $movies = [];
    $paths = glob('src/movies/*.json');
    foreach ($paths as $path) {
        $id = basename($path, '.json');
        $movieJson = json_decode(file_get_contents($path), true);
        $movies[$id] = new Movie($id, $movieJson);
    }
    ksort($movies);
    return $movies;
}

/**
 * CLIPS
 *
 * @return Clip[] Associative array where key is clip int ID and value is a Clip object
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
    krsort($clips);
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
function buildClips(array $clips, array $collectionsIndex, array $movies, array $aspectRatios, array $tags): void
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

        $html = $templates->render('clip', [
            'clip' => $clip,
            'prev' => getPreviousClip($id, $clips),
            'next' => getNextClip($id, $clips),
            'collections' => $collections,
            'movie' => $movie,
            'aspectRatio' => $matchedAspectRatio,
        ]);
        if (!is_dir("build/clip/$id")) {
            mkdir("build/clip/$id", 0777, true);
        }
        file_put_contents("build/clip/$id/index.html", $html);
    }

    $html = $templates->render('clipIndex', ['clips' => $clips, 'tags' => $tags]);
    file_put_contents("build/clip/index.html", $html);
}


/**
 * @param Clip[] $clips
 */
function buildTags(array $tags, array $clips): void
{
    global $templates;
    mkdir('build/tag');
    $tagIndex = [];
    $tagSet = [];
    foreach ($tags as $tag) {
        $filteredClips = [];
        $filterdIds = [];
        foreach ($clips as $clip) {
            if (in_array($tag, $clip->tags)) {
                $filteredClips[] = $clip;
                $filterdIds[] = $clip->id;
            }
        }
        $html = $templates->render('tag', ['tag' => $tag, 'clips' => $filteredClips]);
        if (!is_dir("build/tag/$tag")) {
            mkdir("build/tag/$tag");
        }
        file_put_contents("build/tag/$tag/index.html", $html);

        sort($filterdIds);
        $filterdIdsJson = json_encode($filterdIds);

        $tagSet[] = "\"$tag\" : new Set($filterdIdsJson)";
    }

    $js = 'const tags = {' . implode(",\n", $tagSet) . '}';
    file_put_contents("build/assets/tags.js", $js);

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
 * @param Movie[] $movies
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

/**
 * Generate list of all clips in a const called `allClips` stored in a JS file
 *
 * @param Clip[] $clips
 */
function buildInfos(Array $clips, string $basePath): void
{
    $allClipsCount = count($clips);
    ksort($clips);
    $clipIdsJson = json_encode(array_keys($clips));
    $js = "const allClips = $clipIdsJson;\n";
    $js.= "const basePath = '$basePath';\n";
    $js.= "const allClipsCount = $allClipsCount;\n";
    file_put_contents("build/assets/infos.js", $js);
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
buildClips($clips, $collectionsIndex, $movies, $aspectRatios, $tags);
print '.';
buildTags($tags, $clips);
print '.';
buildCollections($collections, $clips);
print '.';
buildMovies($movies, $clips);
print '.';
buildAspectRatios($aspectRatios, $clips);
print '.';
buildInfos($clips, $basePath);
print '.';


$html = $templates->render('home');
file_put_contents("build/index.html", $html);
print '.';


if (!is_dir("build/assets")) {
    mkdir("build/assets");
}
copy('generator/style/base.css', 'build/assets/base.css');
copy('generator/favicon.png', 'build/assets/favicon.png');
copy('generator/javascript/script.js', 'build/assets/script.js');
print '.';


print "done!\n";
