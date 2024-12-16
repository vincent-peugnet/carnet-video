#!/bin/bash

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

echo "$json" > src/clips/$1.json
echo '💾 updated'
exit 0
