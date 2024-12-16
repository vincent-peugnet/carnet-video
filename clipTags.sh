#!/bin/bash
#
# Interactive tag editor for a given Clip
#
# Provide Clip ID as first argument


clipId=$1

clipTags=$(cat src/clips/$1.json | jq -r  .tags | jq -r 'join(" ")')

options=''

allowedTags=$(cat src/allowedTags)

while read -r line
do
    opt="$line $line"
    if echo $clipTags | grep -wq $line
    then
        opt="$opt on"
    else
        opt="$opt off"
    fi
    options+="$opt "
done <<< "$allowedTags"




eval choices=( $(whiptail --checklist 'tags' 20 60 10 $options 3>&1 1>&2 2>&3) )



choices="$(jq -n '$ARGS.positional' --args "${choices[@]}")"

updatedJson=$(jq --argjson choices "$choices" '.tags = $choices' src/clips/$1.json)



echo "$updatedJson" > src/clips/$1.json
echo '💾 updated'
sleep 0.5
exit 0
