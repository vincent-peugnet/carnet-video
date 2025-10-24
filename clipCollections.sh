#!/bin/bash



options=''


for collection in src/collections/*
do
    collection=$(basename "$collection")
    opt="$collection $collection"
    if grep -wq "$1" "src/collections/$collection"
    then
        opt="$opt on"
    else
        opt="$opt off"
    fi
    options+="$opt "
done


choices="( $(whiptail --checklist 'collections' 20 60 10 $options 3>&1 1>&2 2>&3) )"


for collection in src/collections/*
do
    collection=$(basename "$collection")
    # if [[ $(echo ${choices[@]} | fgrep -w $collection) ]]
    if echo "${choices[@]}" | grep -Fqw "$collection"
    then
        if grep -wq "$1" "src/collections/$collection"
        then
            sleep 0
        else
            echo "➕ added to collection $collection"
            echo "$1 ">> "src/collections/$collection"
        fi
    else
        if grep -wq "$1" "src/collections/$collection"
        then
            sed -i "/$1/d" "src/collections/$collection"
            echo "➖ removed from collection $collection"
        fi
    fi


done


echo '💾 updated'
sleep 0.5
exit 0
