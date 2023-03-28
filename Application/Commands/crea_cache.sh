#!/bin/bash
# php cache.php --crea_elenco="1"

input="../../Logs/elenco_url_da_salvare_in_cache.txt"
while IFS= read -r line
do
	php cache.php --url="it_it/$line"
	echo "it_it/$line"
done < "$input"
