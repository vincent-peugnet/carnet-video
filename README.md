# C clip carnet

Movie clip notebook static site generator.

It goes allong with some scripts to capture the video clips from movies.
See [CAPTURE.md](CAPTURE.md), for more info about this part.

## Dependencies

- bash
- php
    - composer
        - [plates](https://platesphp.com/)
- ffmpeg
- gifsicle
- imagemagick
- jq
- timg (optionnal)
- [TMDB](https://www.themoviedb.org/) account (for movie API)


## Setup

All scripts at the root of directory should have execution permission (including the PHP one)

Write the TMDB API token in a file called `TMDBapiToken`



## Usage

### Building

The generator build from `src` dir to `build` dir. This setting is hardcoded.

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
    📄 allowedTags
```

Movie ID used is the official TMDB id.
The `src/movies` folder is automatically populated during building.

To lauch the build:

    ./build.sh

This will first generate HTML. Then encode thumnails, gifs, and videos files.

### Managing

Main command to view, edit a clip:

    ./display.sh <ClipID>

Will allow you to display all info about a clip.
After what, pressing dedicated key will open editors for description, tags and collections.

This is equivalent to use those commands:

    ./clipDescription.sh <ClipID>
    ./clipTags.sh <ClipID>
    ./clipCollections.sh <ClipID>

To create/edit a collection, it's also possible to use

    ./collection.sh <collectionID> <ClipID>...

And to provide any number of clip to be added to the collection.
