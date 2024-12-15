#!/bin/bash

ffmpeg -hide_banner -i src/clips/$1.mkv -vf scale=854:-2 -c:v libvpx -crf $2 -b:v 2M test/clip$1-480p-vp8-crf$2.webm 
