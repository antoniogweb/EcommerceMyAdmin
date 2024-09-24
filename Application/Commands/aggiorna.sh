#!/bin/bash
cd ../../

# aggiorno admin
echo 1 - Aggiorno il CMS
git pull

# aggiorno Library
echo 2 - Aggiorno la libreria
cd Library
git pull

# faccio il dump del DB
echo 3 - Faccio il dump del DB
mkdir -p ../Logs/Dumps
nome_file=`date +%Y-%m-%d_%H:%M:%S_dump.sql`

while read line; do
	if [[ $line =~ db_name[[:space:]]?\=[[:space:]]?(\'|\")(.*?)(\'|\") ]]; then
		db_name="${BASH_REMATCH[2]}"
	fi
	if [[ $line =~ db_user[[:space:]]?\=[[:space:]]?(\'|\")(.*?)(\'|\") ]]; then
		db_user="${BASH_REMATCH[2]}"
	fi
	if [[ $line =~ db_pwd[[:space:]]?\=[[:space:]]?(\'|\")(.*?)(\'|\") ]]; then
		db_pwd="${BASH_REMATCH[2]}"
	fi
	if [[ $line =~ db_host[[:space:]]?\=[[:space:]]?(\'|\")(.*?)(\'|\") ]]; then
		db_host="${BASH_REMATCH[2]}"
	fi
done < ../config.php

mysqldump -u $db_user -p$db_pwd -h $db_host --no-tablespaces $db_name  > "../Logs/Dumps/$nome_file"

# lancio le migrazoni
echo 4 - Aggiorno il database
cd ../Application/Commands
php migrazioni.php

# comprimo il dump del db
echo 5 - Comprimo il dump creato ed elimino la versione con compressa
cd ../..
tar -zcvf "./Logs/Dumps/$nome_file.tar.gz" "./Logs/Dumps/$nome_file"
rm "./Logs/Dumps/$nome_file"
