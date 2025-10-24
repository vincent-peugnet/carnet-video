#!/bin/bash
#
# Add movie to a collection (collection can be created this way)
#
# collection ID is the first argument
# all the following arguments should be clips ID
#
# This command do not verify if clip exists

collection=$1

touch "src/collections/$collection"

i=1
for clipId in "$@"
do
    if test $i -ne 1
    then
        echo "Acting on $clipId ..."
        echo "$clipId" >> "src/collections/$collection"
    fi
    i=$((i + 1))
done
