#!/bin/bash

if test -z $1
then
    echo 'Please provide a integer as first argument'
    exit 1
fi


if test -w "src/movies/$1.json"
then
    echo "Movie #$1 already imported"
    exit 1
fi



movieJson=$(curl -s "https://www.wikidata.org/wiki/Special:EntityData/$1.json")


instanceOf=$(echo $movieJson | jq -r ".entities.$1.claims.P31[0].mainsnak.datavalue.value.id")


# Q11424    film
# Q24862    short film
# Q202866   animated film
# Q17517379 animated short film
if test "$instanceOf" != 'Q11424' -a "$instanceOf" != 'Q24862' -a "$instanceOf" != 'Q202866' -a "$instanceOf" != 'Q17517379'
then
    echo 'This entity is not a movie'
    exit 1
fi


pageId=$(echo $movieJson | jq ".entities.$1.pageid // \"\"")
movieLabelFr=$(echo $movieJson | jq -r ".entities.$1.labels.fr.value")
originalTitle=$(echo $movieJson | jq -r ".entities.$1.claims.P1476[0].mainsnak.datavalue.value.text // \"\"")
frWiki=$(echo $movieJson | jq -r ".entities.$1.sitelinks.frwiki.url // \"\"")
date=$(echo $movieJson | jq -r ".entities.$1.claims.P577[0].mainsnak.datavalue.value.time // \"\"")



# Get IDs that we will use to do some other requests
directorIds=$(echo $movieJson | jq -r ".entities.$1.claims.P57[].mainsnak.datavalue.value.id // \"\"")
dopId=$(echo $movieJson | jq -r ".entities.$1.claims.P344[0].mainsnak.datavalue.value.id // \"\"")


# Original title, directors and release year are mandatory
if test -z "$originalTitle" -o -z "$date" -o -z "$directorIds"
then
    echo '🚧 movie original title, directors or year is missing'
    exit 1
fi

# extract year from date
year=${date:1:4}

# Convert line separated string to array
readarray -t directorIds <<< "$directorIds"

directors=()
for directorId in "${directorIds[@]}"
do
    directorJson=$(curl -s "https://www.wikidata.org/wiki/Special:EntityData/$directorId.json")
    directorLabelFr=$(echo $directorJson | jq -r ".entities.$directorId.labels.fr.value")
    directors+=("$directorLabelFr")
done

directorsJson=$(jq --compact-output --null-input '$ARGS.positional' --args -- "${directors[@]}")



if test -n "$dopId"
then
    dopJson=$(curl -s "https://www.wikidata.org/wiki/Special:EntityData/$dopId.json")
    dopLabelFr=$(echo $dopJson | jq -r ".entities.$dopId.labels.fr.value")
else
    dopLabelFr=''
fi




if test "$2" != 'y'
then
    echo "title: $movieLabelFr"
    echo "original title: $originalTitle"
    echo "<$frWiki>"
    echo "year: $year"
    echo "director(s): ${directors[@]}"
    echo "dop: $dopLabelFr"
    echo '================================'
    echo 'confirmation ? [y]/n'
    read -rsn1 input
    if test "$input" = 'n'
    then
        echo '❌ aborted'
        exit 1
    fi
fi



jq -n   --arg title "$movieLabelFr" \
        --arg originalTitle "$originalTitle" \
        --arg year "$year" \
        --arg dop "$dopLabelFr" \
        --arg wiki "$frWiki" \
        --argjson directors "$directorsJson" \
    '{
        title: $title,
        originalTitle: $originalTitle,
        year: $year,
        directors: $directors,
        dop: $dop,
        wiki: $wiki
    }
    | .year |= tonumber
    | if .dop == "" then .dop |= null else . end
    | if .wiki == "" then .wiki |= null else . end
    | if .title == "" then .title |= null else . end' \
    > "src/movies/$1.json"


echo "Successfully imported movie #$1"
exit 0

