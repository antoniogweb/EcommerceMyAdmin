#!/bin/bash

# Define the target directory
directory="$( dirname -- "$0"; )/../../../images/ticket_video"

# Check if the target is not a directory
if [ ! -d "$directory" ]; then
  exit 1
fi

estTxt="txt"

#Loop through files in the target directory
for file in "$directory"/*; do
  if [ -f "$file" ]; then
    fileName="$(basename -- $file)"
	
	est="${fileName##*.}"
	
	if [[ $est == $estTxt ]]; then
		echo "$fileName"
	fi
  fi
done
