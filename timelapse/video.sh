#!/bin/bash

year=`date +%Y --date='0 day'`
month=`date +%2m --date='0 day'`
mes=`date +%b --date='0 day'`
day=`date +%2d --date='0 day'`
path_media="/media/CAM"
path_pi="/home/pi/links"
echo $year$month$mes$day
mkdir $path_pi
rm $path_pi/*.*
ls -l /media/CAM/$year/$mes/$day/*.jpg | awk 'BEGIN{contador=1;FS="/"}{printf("ln -s %s/%s/%s/%s/%s %s/lapse%.4d.jpg\n","'$path_media'","'$year'","'$mes'","'$day'",$7,"'$path_pi'",contador);contador++}END{}' | bash
#ls -l /media/CAM/$year/$month/$day/*.* | awk 'BEGIN{contador=1;FS="/"}{print $7}END{}'
avconv -i ''$path_pi'/lapse%04d.jpg' -r 20 -qscale 5 -s hd720 '/media/CAM/'$year'/'$mes'/'$day'/'$year''$month''$day'.avi'

