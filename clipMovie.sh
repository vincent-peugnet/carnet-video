#!/bin/bash

if test -z $1
then
    echo 'missing clip ID as first argument'
    exit 1
fi

if test ! -f "src/clips/$1.json"
then
    echo 'no JSON file found for this clip ID'
    exit 1
fi

clear

echo 'Is it one of the following movies ?'

lastMovieFiles=$(ls -Art src/movies | tail -n 6)
i=0
movies=()
titles=()
for file in $lastMovieFiles
do
    title=$(jq -r .title src/movies/$file)
    year=$(jq -r .year src/movies/$file)
    echo -e "[$i] $title - \e[37m$year\e[0m"
    titles+=("$title")

    movie="${file%.*}"
    movies+=("$movie")
    let i++
done
echo '[ ] Not in the list ! 👻'



read -rsn1 input # get 1 character

case $input in
    0|1|2|3|4|5) 
        echo "Movie: ${titles[$input]}"
        movie="${movies[$input]}"
        ;;
    *)
        echo 'What is the movie TMDB identifier ? (press Enter to send, leave empty to abort)'
        read movie

        if test -z "$movie"
        then
            echo '❌ cancelling'
            sleep 0.5
            ./display $1
            exit 1
        fi

        echo "📥️ fetching data for movie #$movie"
        apiToken=$(cat config/TMDBapiToken)
        json=$(curl -s --request GET \
            --url "https://api.themoviedb.org/3/movie/$movie" \
            --header "Authorization: Bearer $apiToken")

        success=$(echo "$json" | jq -r .success)
        if test "$success" != 'false'
        then
            title=$(echo "$json" | jq -r '.title')
            release_date=$(echo "$json" | jq -r '.release_date')
            year=${release_date::4}

            echo "$title - $year"

            echo 'confirmation ? [y]/n'
            read -rsn1 input
            if test $input = 'n'
            then
                echo '❌ aborted'
                sleep 1
                exit 1
            fi

            jq -nc --arg title "$title" --arg year "$year" \
                '{
                    title: $title,
                    year: $year
                }' >> "src/movies/$movie.json"
        else
            echo '⛔️ error while trying to fetch movie data on TMDB:'
            echo "$json" | jq -r .status_message
            exit 1
        fi
        ;;
esac


clip=$(jq --arg movie "$movie" '.movie = $movie' src/clips/$1.json)
echo "$clip" | jq '.movie |= tonumber' > "src/clips/$1.json"

echo '💾 updated'
sleep 1
