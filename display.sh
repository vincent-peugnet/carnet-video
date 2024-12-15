#!/bin/bash


clipId=$1

movieId=$(cat src/clips/$1.json | jq .movie)
description=$(cat src/clips/$1.json | jq -r .description)
tags=$(cat src/clips/$1.json | jq -r  .tags | jq -r 'join(" ")')
collections=()
for collection in src/collections/*
do
    if grep -wq $1 "$collection"
    then
        collections+=("${collection##*/}")
    fi
done

timg -g60x30 --frames=1 src/clips/$1.mkv

echo "<http://localhost:8066/clip/$1/>"
echo '🎞️  movie:' $movieId
echo '📄 description:' $description
echo '🎟️  tags:' "${tags[@]}"
echo "📜  collections: ${collections[@]}"
# echo ------------------------------------------
echo -e "\e[37m[T]: edit tags, [C]: edit collections, [D]: edit description, [P]: play clip\e[0m"

while true
do
    read -s -n 1 input
    case $input in
        t|T)
            ./clipTags.sh $1
            ./display.sh $1
            exit
            ;;
        c|C)
            ./clipCollections.sh $1
            ./display.sh $1
            exit
            ;;
        p|P)
            echo '👁️  play !!'
            xdg-open src/clips/$1.mkv
            ;;
        d|D)
            ./clipDescription.sh $1
            ./display.sh $1
            exit
            ;;
        *)
            echo 'bye 👋'
            exit
            ;;
    esac
done
