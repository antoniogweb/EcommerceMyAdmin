#!/bin/bash
# Elimino la cache HTML
rm -fR ../../../Logs/cachehtml

# Elimino la cache dei metodi
rm -fR ../../../Logs/CacheMethods

# Elimino l'elenco degli URL da salvare in cache
rm -f ../../Logs/elenco_url_da_salvare_in_cache.txt

php cache.php --crea_elenco="1"

input="../../Logs/elenco_url_da_salvare_in_cache.txt"

# Ciclo la prima volta per settare i permessi alla cartella
i=0
while IFS= read -r line
do
	if [[ "$i" == '1' ]]
	then
		chmod -fR 777 ../../../Logs/cachehtml
		break
	fi
	php cache.php --url="it_it/$line"
	((i++))
done < "$input"

# Riparti con il ciclo
while IFS= read -r line
do
	OUTPUT=$(php cache.php --url="it_it/$line")
# 	echo "../../../Logs/cachehtml/${OUTPUT}"
	chmod -f 777 "../../../Logs/cachehtml/${OUTPUT}"
	
# 	php cache.php --url="it_it/$line"
	echo "it_it/$line"
done < "$input"

chmod -R 777 ../../../Logs/cachehtml
