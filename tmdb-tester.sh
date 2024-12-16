#!/bin/bash
#
# Check output of TMDB movie GET API
#
# Provide a movie ID as first argument

apiToken=$(cat config/TMDBapiToken)

curl -s --request GET \
     --url "https://api.themoviedb.org/3/movie/$1" \
     --header "Authorization: Bearer $apiToken" \
     | jq .
