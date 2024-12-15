#!/bin/bash


for path in src/clips/*.mkv
do
    basepath="${path%.*}" # path without file extension
    id="${basepath##*/}" # just filename
    if test ! -f "$basepath.json"
    then
        cp generator/clip.json "$basepath.json"
        echo "created default JSON file for clip #$id"
    fi
done

mkdir -p 'build'

./build-html.php
./syncMovies.sh
./build-thumbnail.sh

echo -e "🏁 \033[1mbuilding completed !\033[0m"
