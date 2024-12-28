#!/bin/bash


echo -e "🎫 \033[1msyncing movie metadatas\033[0m"

for path in src/clips/*.json
do
    basepath="${path%.*}" # path without file extension
    id="${basepath##*/}" # just filename
    movie=$(jq -re .movie $path)
    if test $? = 1 -o -z $movie
    then
        echo "❓️ missing movie for clip #$id"
        continue
    fi
    if test ! -f "src/movies/$movie.json"
    then
        ./fetchMovie.sh "$movie" y
    fi
done
