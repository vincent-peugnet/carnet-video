#!/bin/bash

if test -z $1
then
    clear
    echo '🚕 Type a Clip ID and press enter !'
    read clip
    ./display.sh $clip
    exit
fi

clear

if test ! -f src/clips/$1.json
then
    ./clipMetadata.sh $1 # try to create JSON file if MKV exist
    if test $? != 0      # this mean clipMetadata failed, (no MKV an no JSON)
    then
        echo -e "\e[37m[←]prev [→]next [↑]id\e[0m"
        escape_char=$(printf "\u1b")
        read -rsn1 input # get 1 character
        if [[ $input == $escape_char ]]; then
            read -rsn2 input # read 2 more chars
        fi
        case $input in
        '[C')
            n=$(($1+1))
            ./display.sh $n
            exit
            ;;
        '[D')
            n=$(($1-1))
            ./display.sh $n
            exit
            ;;
        '[A')
            clear
            echo '🚕 Type a Clip ID and press enter !'
            read clip
            ./display.sh $clip
            exit
            ;;
        *)
            echo 'bye 👋'
            exit
            ;;
    esac
    fi
fi

movieId=$(jq -e .movie src/clips/$1.json)
description=$(jq -r .description src/clips/$1.json)
aspectRatio=$(jq -r .aspectRatio src/clips/$1.json)
duration=$(jq -r .duration src/clips/$1.json)
tags=$(cat src/clips/$1.json | jq -r  .tags | jq -r 'join(" ")')
collections=()
for collection in src/collections/*
do
    if grep -wq $1 "$collection"
    then
        collections+=("${collection##*/}")
    fi
done

if test $aspectRatio = null -o $duration = null
then
    ./clipMetadata.sh $1
    ./display.sh $1
    exit
fi



h=$(tput lines)
h=$(($h-7))

timg   -gx$h  --frames=1 src/clips/$1.mkv

echo -e "\033[1mClip #$1\033[0m  | ⏱️  $duration s  | ➗ $aspectRatio   <http://localhost:8066/clip/$1/>"
echo '🎞️  movie:' $movieId
echo '📄 description:' $description
echo '🎟️  tags:' "${tags[@]}"
echo "📜  collections: ${collections[@]}"
# echo ------------------------------------------
echo -e "\e[37m[T]ags [C]ollections [D]escription [M]ovie, [P]lay [←]prev [→]next [↑]id\e[0m"

escape_char=$(printf "\u1b")
read -rsn1 input # get 1 character
if [[ $input == $escape_char ]]; then
    read -rsn2 input # read 2 more chars
fi
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
        sleep 1
        ./display.sh $1
        ;;
    d|D)
        ./clipDescription.sh $1
        ./display.sh $1
        exit
        ;;
    m|M)
        ./clipMovie.sh $1
        ./display.sh $1
        exit
        ;;
    '[C')
        n=$(($1+1))
        ./display.sh $n
        exit
        ;;
    '[D')
        n=$(($1-1))
        ./display.sh $n
        exit
        ;;
    '[A')
        clear
        echo '🚕 Type a Clip ID and press enter !'
        read clip
        ./display.sh $clip
        exit
        ;;
    *)
        echo 'bye 👋'
        exit
        ;;
esac

