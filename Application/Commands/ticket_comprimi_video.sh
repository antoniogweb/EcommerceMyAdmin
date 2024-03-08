#!/bin/bash

# Define the target directory
rootPath="$( dirname -- "$0"; )/../../.."
directory="$( dirname -- "$0"; )/../../../images/ticket_video"
directoryImmagini="$( dirname -- "$0"; )/../../../images/ticket_immagini"

# echo $rootPath

# Check if the target is not a directory
if [ ! -d "$directory" ]; then
  exit 1
fi

echo -e "[$(date '+%Y-%m-%d %X')]\tINIZIO COMPRESSIONE VIDEO" >> "$rootPath/admin/Logs/compressione_video.log"

estTxt="txt"

#Loop through files in the target directory
for file in "$directory"/*; do
  if [ -f "$file" ]; then
    fileName="$(basename -- $file)"
	
	est="${fileName##*.}"
	
	if [[ $est == $estTxt ]]; then
		fileNameSenzaEstensione="${fileName%.*}"
		
# 		echo $fileName
# 		echo $fileNameSenzaEstensione
		
		if [ -f "$directoryImmagini/$fileNameSenzaEstensione" ]; then
			fileVideo="$directoryImmagini/$fileNameSenzaEstensione"
			
			echo "Comprimo il file $fileNameSenzaEstensione"
			echo -e "[$(date '+%Y-%m-%d %X')]\tComprimo il file $fileNameSenzaEstensione" >> "$rootPath/admin/Logs/compressione_video.log"
			
			estVideo="${fileNameSenzaEstensione##*.}"
			tempVideo="$directoryImmagini/tempvideo.$estVideo"
			
			ffmpeg -y -i "$fileVideo" -vf "scale=trunc(iw/4)*2:trunc(ih/4)*2" -c:v libx265 -crf 28 "$tempVideo"
			
			RESULT=$?
			
			if [ $RESULT -eq 0 ]; then
				mv "$tempVideo" "$fileVideo"
			else
				rm -f "$tempVideo"
				rm -f "$fileVideo"
				
				echo "Errore compressione del file $fileNameSenzaEstensione, lo elimino"
				echo -e "[$(date '+%Y-%m-%d %X')]\tErrore compressione del file $fileNameSenzaEstensione, lo elimino" >> "$rootPath/admin/Logs/compressione_video.log"
			fi
		fi
		
		rm -f "$directory/$fileNameSenzaEstensione.txt"
	fi
  fi
done

echo -e "[$(date '+%Y-%m-%d %X')]\tFINE COMPRESSIONE VIDEO" >> "$rootPath/admin/Logs/compressione_video.log"
