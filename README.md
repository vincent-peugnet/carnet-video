# Carnet Video

This is a custom static site generator coded for the website: <https://vincent.club1.fr/carnet-video/>

It produce a movie clip notebook accessible from the Web from a folder containing videos file and JSON metadata.
Management is done thanks to an interactive CLI (this get close to a TUI)

It goes allong with some scripts to capture the video clips from movies using MPV plugins.
See [CAPTURE.md](CAPTURE.md), for more info about this part.

## ⚠️ limitation

Currently the **HTML templates are hardcoded in french** (home page is hardcoded too).
It's done like this as I'm the only user of this tool.
If you're interested in using it, please contact me at <vincent+carnet@club1.fr>.
It may be motivating enough for me to find a way to include other users !

## Setup

Installation process (for Debian or derivates)

### Dependencies

This programm is supposed to depend only on packages that are in Debian. (that's a design strategy)
It use composer to call a few PHP packages.

- packages
    - ffmpeg
    - mediainfo
    - imagemagick
    - jq
    - timg
    - php
    - composer
        - [plates](https://platesphp.com/)
- Wikidata (online service)

Command to install dependencies (on Debian)

    sudo apt install ffmpeg mediainfo imagemagick jq timg php composer


### Install

Go to desired folder.
Then clone the git repo in current folder.

    git clone https://github.com/vincent-peugnet/carnet-video/ .


Install composer dependencies

    composer install


All scripts at the root of directory should have execution permission (including the PHP one).

    chmod +x *.sh build-html.php


Create base directories and files for "src" and "config".

    mkdir -p src/clips src/collections src/movies config
    touch src/tags src/aspectRatios config/newClip config/importDirectory


### Configure

- Fill `config/newClip` with number `1`
- Fill `config/importDirectory` with the temporary path where new video files are captured.
It's recommended to use an absolute path (starting with a `/`)
- If the site will be published inside subfolers, fill `config/basePath` with the name of the path that lead to the site. (⚠️ witout leading or trailing slashes)


## Use

### Architecture
```
📁 src
    📁 clips
        🎞️ <clipId>.mkv
        📄 <clipId>.json
        ...
    📁 collections
        📄 <collectionID>
        ...
    📁 movies
        📄 <movieId>.json
        ...
    📄 tags
    📄 aspectRatios
```

Movie ID used is the official Wikidata id.
The `src/movies` folder is automatically populated during building.

### Import new video clips

This programm assumes you have a folder that contain newly captured video file(see [CAPTURE.md](CAPTURE.md)).
This directory have to be declared in the file `config/importDirectory`.
The ID of the future clip have to be a valid number in `config/newClip`.
This number will be auto incremented during each import phase.

To import clip use the follow command:

    ./importClips.sh


### Manage

Main command to view, edit a clip:

    ./display.sh <ClipID>

Will allow you to display all info about a clip.
After what, pressing dedicated key will open editors for description, tags and collections.

This is equivalent to use those commands:

    ./clipDescription.sh <ClipID>
    ./clipTags.sh <ClipID>
    ./clipCollections.sh <ClipID>
    ./clipMovie.sh <ClipID>

To create/edit a collection, it's also possible to use

    ./collection.sh <collectionID> <ClipID>...

And to provide any number of clip to be added to the collection.


### Build

The generator build from `src` dir to `build` dir. This folders are hardcoded.

To lauch the build:

    ./build.sh

This will first encode thumnails, previews (animated webp), and videos files. Then check for clip's movies. And finally generate HTML.


## Infos


### Encoding

Quality setting for video is hardcoded. in file `build-thumbnail.sh`
Resolution output depend on aspect ratio.
video with aspect ratio under 2 are encoded to 480p and above use 576p.

