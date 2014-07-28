#!/bin/bash

########################################
#############instalador#################
########################################

mkdir /home/pi/timelapse/
cp ./timelapse/*.* /home/pi/timelapse/
cd /home/pi/timelapse/
chmod 777 /home/pi/timelapse/*.*
gcc -o timelapse timelapse.c -l bcm2835
cd -
cp ./www/*.* /var/www/
chmod 777 /var/www/*.*
crontab ./varios/cron.txt
