#!/bin/sh

rsync -avH --exclude index.php --exclude records.php --exclude weeklyreports.php /media/kkimani/ /var/www/winners 
