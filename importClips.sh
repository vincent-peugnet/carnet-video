#!/bin/bash

newClip=$(cat config/newClip)

if test -z "$newClip"
then
    echo 'missing new clip ID'
    echo 'edit file "config/newClip"'
    exit 1
fi

if test -f "src/clips/$newClip.mkv"
then
    echo 'clip ID stored in config/newClip is already used'
    exit 1
fi

importDirectory=$(cat config/importDirectory)
importDirectory=$(echo "$importDirectory" | sed 's:/*$::')

if test -z "$importDirectory" -o ! -d "$importDirectory"
then
    echo 'error with import Directory'
    echo 'create or edit file "config/importDirectory"'
    exit 1
fi

echo "reading import directory: '$importDirectory'"

directoryContent=$(ls -A "$importDirectory")

if test -z "$directoryContent"
then
    echo 'directory is empty'
    exit 1
fi

clips="$importDirectory/*.mkv"
i=0
for path in $clips
do
    mv "$path" "src/clips/$newClip.mkv"
    if test -f "src/clips/$newClip.mkv"
    then
        echo "imported clip #$newClip"
        let newClip++
        echo "$newClip" > 'config/newClip'
        let i++
    else
        echo "error while importing clip"
    fi
done

echo "$i clips where imported"
