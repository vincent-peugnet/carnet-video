#!/bin/bash
#
# Lauch local server at http://localhost:8066

basePath="$(cat config/basePath)"


if test -n "$basePath"
then
    tmpServer="$(mktemp -d)"
    dir="$(dirname "$basePath")"
    if test "$dir" = '.'
    then
        dir=''
    fi
    link="$(basename "$basePath")"
    mkdir -p "$tmpServer/$dir"
    ln -s "$PWD/build" "$tmpServer/$dir/$link"
    docRoot="$tmpServer"
else
    docRoot='build'
fi


echo "<http://localhost:8066/$basePath/>"


php -S localhost:8066 -t $docRoot
