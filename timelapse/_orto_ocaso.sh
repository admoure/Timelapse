#!/bin/bash

dia=$(date -u +%d)
mes=$(date -u +%B)

month=$(echo $mes | tr [:lower:] [:upper:])

cat /home/pi/timelapse/ortocas.txt | awk 'BEGIN{}
				{
					if ("'$dia'" - $1 == 0)
					{
						if("'$month'"=="ENERO")
						{
							print $2 $3;
						}
						if("'$month'"=="FEBRERO")
						{
							print $4 $5
						}
						if("'$month'"=="MARZO")
						{
							print $6 $7
						}
						if("'$month'"=="ABRIL")
						{
							print $8 $9
						}
						if("'$month'"=="MAYO")
						{
							print $10 $11
						}
						if("'$month'"=="JUNIO")
						{
							print $12 $13

						}
						if("'$month'"=="JULIO")
						{
							print $14 $15

						}
						if("'$month'"=="AGOSTO")
						{
							print $16 $17

						}
						if("'$month'"=="SEPTIEMB.")
						{
							print $18 $19

						}
						if("'$month'"=="OCTUBRE")
						{
							print $20 $21

						}
						if("'$month'"=="NOVIEMB.")
						{
							print $22 $23
						}
						if("'$month'"=="DICIEMB.")
						{
							print $24 $25

						}
					}
				}END{}'


