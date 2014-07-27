#!/bin/bash

resta=2;
numero=$[$1-$resta]
dias="+"$numero
find /media/CAM/ -maxdepth 3 -mindepth 3 -mtime $dias | awk 'BEGIN{}{printf("rm -r %s\n",$0)}END{}' | bash
