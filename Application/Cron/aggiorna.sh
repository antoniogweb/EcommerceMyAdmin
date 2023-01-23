#!/bin/sh
cd ../../

# aggiorno admin
echo aggiorno il CMS
git pull

# aggiorno Library
echo Aggiorno la libreria
cd Library
git pull

# lancio le migrazoni
echo Aggiorno il database
cd ../Application/Cron
php migrazioni.php
