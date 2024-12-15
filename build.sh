#!/bin/bash

php build-html.php
./syncMovies.sh
./build-thumbnail.sh
