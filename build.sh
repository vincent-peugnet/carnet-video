#!/bin/bash


mkdir -p 'build'

./build-thumbnail.sh
./syncMovies.sh
./build-html.php

echo -e "🏁 \033[1mbuilding completed !\033[0m"
