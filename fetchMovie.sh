#!/bin/bash
#
# Usage:
#
# ./getchMovie.sh <TMDBid> [y]
#
# Provide optionnal second parameter 'y' avoid asking for confirmation

if test -z $1
then
    echo 'Please provide a integer as first argument'
    exit 1
else
    movie="$1"
fi

if test -w "src/movies/$movie.json"
then
    echo "Movie #$movie already imported"
    exit 1
fi


echo "📥️ fetching data for movie #$movie"
apiToken=$(cat config/TMDBapiToken)
json=$(curl -s --request GET \
    --url "https://api.themoviedb.org/3/movie/$movie" \
    --header "Authorization: Bearer $apiToken")

success=$(echo "$json" | jq -r .success)
if test ! "$success" != 'false'
then
    echo '⛔️ error while trying to fetch movie data on TMDB:'
    echo "$json" | jq -r .status_message
    exit 1
fi

title=$(echo "$json" | jq -r '.title')
release_date=$(echo "$json" | jq -r '.release_date')
year=${release_date::4}


if test "$2" != 'y'
then
    echo "$title - $year"
    echo 'confirmation ? [y]/n'
    read -rsn1 input
    if test "$input" = 'n'
    then
        echo '❌ aborted'
        exit 1
    fi
fi

jq -nc --arg title "$title" --arg year "$year" \
    '{
        title: $title,
        year: $year
    }' > "src/movies/$movie.json"

echo "Successfully imported movie #$movie"
sleep 0.5
exit 0
