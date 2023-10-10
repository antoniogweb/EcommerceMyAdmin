#!/bin/bash

path="$( dirname -- "$0"; )/../../../"

# while getopts "p:" opt
# do
#    case "$opt" in
#       p ) path="$OPTARG" ;;
#    esac
# done

echo -e "[$(date '+%Y-%m-%d %X')]\tINIZIO CREAZIONE CACHE" >> "$path/admin/Logs/cache_sito.log"

touch "$path/Logs/caching.log"

# Elimino la cache HTML
rm -fR "$path/Logs/cachehtml"

# Elimino la cache dei metodi
rm -fR "$path/Logs/CacheMethods"

# Elimino l'elenco degli URL da salvare in cache
rm -f "$path/admin/Logs/elenco_url_da_salvare_in_cache.txt"

php "$path/admin/Application/Commands/cache.php" --crea_elenco="1"

input="$path/admin/Logs/elenco_url_da_salvare_in_cache.txt"

# Parti con il ciclo
while IFS= read -r line
do
	# DESK
	OUTPUT=$(php "$path/admin/Application/Commands/cache.php" --url="it_it/$line" --dispositivo="_DESK")
	
	# PHONE
	OUTPUT=$(php "$path/admin/Application/Commands/cache.php" --url="it_it/$line" --dispositivo="_PHONE")
	
	echo "it_it/$line"
done < "$input"

chmod -fR 777 "$path/Logs/CacheMethods"
chmod -R 777 "$path/Logs/cachehtml"

rm -f "$path/Logs/caching.log"

echo -e "[$(date '+%Y-%m-%d %X')]\tFINE CREAZIONE CACHE" >> "$path/admin/Logs/cache_sito.log"
