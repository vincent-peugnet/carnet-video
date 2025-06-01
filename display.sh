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

# If JSON file does not exit
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

movieId=$(jq -r .movie src/clips/$1.json)
description=$(jq -r .description src/clips/$1.json)
aspectRatio=$(jq -r .aspectRatio src/clips/$1.json)
duration=$(jq -r .duration src/clips/$1.json)
color=$(jq -r .color src/clips/$1.json)
tags=$(cat src/clips/$1.json | jq -r  .tags | jq -r 'join(" ")')
collections=()
for collection in src/collections/*
do
    if grep -wq $1 "$collection"
    then
        collections+=("${collection##*/}")
    fi
done

if test -n "$movieId" -a "$movieId" != 'null'
then
    if test -f "src/movies/$movieId.json"
    then
        title=$(jq -r .title src/movies/$movieId.json)
        year=$(jq -r .year src/movies/$movieId.json)
        movie="$title - $year   (#$movieId)"
    else
        movie="#$movieId (no movie data fetched)"
    fi
else
    movie='❓️'
fi

if test "$aspectRatio" = null -o "$duration" = null -o "$color" = null
then
    ./clipMetadata.sh "$1"
    sleep 1
    ./display.sh "$1"
    exit
fi

hex=$(echo "$color" | awk '{print substr($0,2)}')
r=$(printf '0x%0.2s' "$hex")
g=$(printf '0x%0.2s' ${hex#??})
b=$(printf '0x%0.2s' ${hex#????})
cc=$(echo -e `printf "%03d" "$(((r<75?0:(r-35)/40)*6*6+(g<75?0:(g-35)/40)*6+(b<75?0:(b-35)/40)+16))"`)
cc='\e[38;5;'$cc'm'
color="$cc██\e[0m"

h=$(tput lines)
h=$(($h-7))

# if the thumbnail as already been generated, use it. Otherwise, read the first frame of movie file.
if test -f "build/assets/thumbnail/$1.webp"
then
    timg -gx$h "build/assets/thumbnail/$1.webp"
else
    timg   -gx$h  --frames=1 "src/clips/$1.mkv"
fi


echo -e "\033[1mClip #$1\033[0m   ⏱️  $duration s   ➗ $aspectRatio  🎨 $color  <http://localhost:8066/clip/$1/>"
echo '🎞️  movie:' $movie
echo "📄 description: $description"
echo '🎟️  tags:' "${tags[@]}"
echo "📜  collections: ${collections[@]}"
# echo ------------------------------------------
echo -e "\e[37m[T]ags [C]ollections [D]escription [M]ovie, [P]lay [←]prev [→]next [↑]id\e[0m"

escape_char=$(printf "\u1b")
read -rsn1 input # get 1 character
if [[ $input == "$escape_char" ]]
then
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
        ;;
    d|D)
        ./clipDescription.sh $1
        ./display.sh $1
        exit
        ;;
    m|M)
        ./clipMovie.sh "$1"
        ./display.sh "$1"
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

