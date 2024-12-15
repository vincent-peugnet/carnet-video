#!/bin/bash
#
# Interactive description editor for a given Clip
#
# Provide Clip ID as first argument


clipId=$1

description=$(jq -r  .description src/clips/$1.json)

echo "$description" > /tmp/carnet-desc-$1.txt
$EDITOR "/tmp/carnet-desc-$1.txt"

description=$(cat /tmp/carnet-desc-$1.txt)

updatedJson=$(jq --arg description "$description" '.description = $description' src/clips/$1.json)



echo "$updatedJson" > src/clips/$1.json
echo '💾 updated'
