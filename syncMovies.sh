#!/bin/bash

apiToken=$(cat TMDBapiToken)


for path in src/clips/*.json
do
    movie=$(jq -re .movie $path)
    if test $? = 1
    then
        echo "❓️ missing movie for file <$path>"
        continue
    fi
    if test ! -f "src/movies/$movie.json"
    then
        echo "📥️ fetching data for movie #$movie"
        json=$(curl -s --request GET \
            --url "https://api.themoviedb.org/3/movie/$movie" \
            --header "Authorization: Bearer $apiToken")

        success=$(echo "$json" | jq -r .success)
        if test "$success" != 'false'
        then
            title=$(echo "$json" | jq -r '.title')
            release_date=$(echo "$json" | jq -r '.release_date')
            year=${release_date::4}

            jq -nc --arg title "$title" --arg year "$year" \
                '{
                    title: $title,
                    year: $year
                }' >> "src/movies/$movie.json"
        else
            echo '⛔️ error while trying to fetch movie data on TMDB:'
            echo "$json" | jq -r .status_message
            echo "Listed on file <$path>"
        fi
    fi

done
