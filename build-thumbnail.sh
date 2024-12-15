#!/bin/bash

rm -rf /tmp/carnet-video
mkdir /tmp/carnet-video
mkdir /tmp/carnet-video/thumbnail
mkdir /tmp/carnet-video/clip

mkdir -p build/assets/thumbnail

for clip in src/clips/*.mkv
do
    id="${clip##*/}"
    id="${id%.*}"

    if test ! -f "build/assets/thumbnail/$id.webp"
    then
        ffmpeg -hide_banner -loglevel error -i "$clip" -ss 00:00:00 -frames:v 1 "/tmp/carnet-video/thumbnail/$id.png"
        convert /tmp/carnet-video/thumbnail/$id.png -thumbnail 300x300 "/tmp/carnet-video/thumbnail/$id.webp"
        mv "/tmp/carnet-video/thumbnail/$id.webp" "build/assets/thumbnail/$id.webp"
        echo "🖼️ generated webp thumbnail for clip #$id"
    fi

    if test ! -f "build/assets/thumbnail/$id.gif"
    then
        mkdir /tmp/carnet-video/thumbnail/$id
        ffmpeg -hide_banner -loglevel error -i "$clip" -r 0.5 -vf scale=300:-1 /tmp/carnet-video/thumbnail/$id/%04d.gif
        gifsicle --delay=20 --optimize --optimize  /tmp/carnet-video/thumbnail/$id/*.gif > /tmp/carnet-video/thumbnail/$id.gif
        mv "/tmp/carnet-video/thumbnail/$id.gif" "build/assets/thumbnail/$id.gif"
        echo "🖼️ generated gif thumbnail for clip #$id"
    fi
    

    if test ! -f "build/assets/clip/$id.webm"
    then
        ffmpeg -hide_banner -loglevel error -i "$clip" -vf scale=854:-2 -c:v libvpx -crf 10 -b:v 800k -b:a 64k /tmp/carnet-video/clip/$id.webm
        mv /tmp/carnet-video/clip/$id.webm build/assets/clip/$id.webm
        echo "🎞️ generated compressed video for clip #$id"
    fi
    

done
