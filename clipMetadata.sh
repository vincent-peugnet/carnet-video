#!/bin/bash
#
# Provide the Clip JSON file with some metadata measured on the video file:
# Aspect Ratio, Duration and Average Color
#
# First argument is the clip ID

if test ! -f "src/clips/$1.mkv"
then
    echo "⛔️  no clip for ID #$1"
    exit 1
fi


clip="src/clips/$1.mkv"

aspectRatio=$(mediainfo --Inform="Video;%DisplayAspectRatio%" $clip | cut -c -4)
d=$(mediainfo --Inform="General;%Duration%" $clip | sed 's/.\{3\}$//')


if test ! -f src/clips/$1.json
then
    cp generator/clip.json src/clips/$1.json
fi

json=$(jq --arg aspectRatio "$aspectRatio" --arg d "$d" '.duration = $d | .aspectRatio = $aspectRatio | .aspectRatio |= tonumber | .duration |= tonumber' src/clips/$1.json)

if test $(jq -r .color src/clips/$1.json) = null
then
    rm -rf /tmp/carnet-video/cover
    mkdir -p /tmp/carnet-video/cover
    ffmpeg -hide_banner -loglevel error -ss 00:00:00 -i "$clip" -frames:v 1 "/tmp/carnet-video/cover/$1.png"
    color=$(convert "/tmp/carnet-video/cover/$1.png" -resize 1x1 txt:- | grep -Po "#[[:xdigit:]]{6}")
    json=$(echo $json | jq --arg color "$color" '.color = $color')
    echo "🎨 measure average color for clip #$1"
fi

echo "$json" > src/clips/$1.json
echo "💾 updated metadata for clip #$1"
exit 0
