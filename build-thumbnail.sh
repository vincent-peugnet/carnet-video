#!/bin/bash

newLineIfFirst() {
    if test $counter = 0
    then
        echo
    fi
}

echo -ne "🗜️  \033[1mmissing thumbnails or video files\033[0m"

rm -rf /tmp/carnet-video
mkdir -p /tmp/carnet-video/thumbnail
mkdir -p /tmp/carnet-video/clip

mkdir -p build/assets/thumbnail
mkdir -p build/assets/preview
mkdir -p build/assets/clip

counter=0

for clip in src/clips/*.mkv
do
    id=$(basename -s '.mkv' "$clip")

    if test ! -f "build/assets/thumbnail/$id.webp"
    then
        ffmpeg -hide_banner -loglevel error -ss 00:00:00 -i "$clip" -vf "scale='300:trunc(300/dar)',setsar=1/1" -frames:v 1 "/tmp/carnet-video/thumbnail/$id.png"
        convert /tmp/carnet-video/thumbnail/$id.png -thumbnail 300x300 "/tmp/carnet-video/thumbnail/$id.webp"
        mv "/tmp/carnet-video/thumbnail/$id.webp" "build/assets/thumbnail/$id.webp"
        newLineIfFirst
        echo "🖼️  generated webp thumbnail for clip #$id"
        let counter++
    fi

    if test ! -f "build/assets/preview/$id.webp"
    then
        ffmpeg -hide_banner -loglevel error -ss 00:00:00 -to 00:00:05 -r 200 -i "$clip" -vf "scale='300:trunc(300/dar)',setsar=1/1" -compression_level 6 -q:v 35 -loop 1 -r 7 "build/assets/preview/$id.webp"
        newLineIfFirst
        echo "🖼️  generated webp preview for clip #$id"
        let counter++
    fi
    

    if test ! -f "build/assets/clip/$id.webm"
    then
        ar=$(mediainfo --Inform="Video;%DisplayAspectRatio%" $clip)
        if [[ $ar == 2* ]]
        then
            width=1024
        else
            width=854
        fi

        ffmpeg -hide_banner -loglevel error -i "$clip" -vf "scale='$width:trunc($width/dar)',setsar=1/1" -c:v libvpx -crf 10 -b:v 1500k -crf 30 -b:a 64k /tmp/carnet-video/clip/$id.webm
        mv /tmp/carnet-video/clip/$id.webm build/assets/clip/$id.webm
        newLineIfFirst
        echo "🎞️ generated compressed video for clip #$id"
        let counter++
    fi


    if test $counter = 0
    then
        echo -n '.'
    fi

done


if test $counter = 0
then
    echo 'done !'
else
    echo "done! encoded: $counter file(s)"
fi
