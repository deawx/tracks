#!/bin/bash

# To import a gpx file into a mysql dbase...

DBASE=geo
USER=rsigler
PASSWD=2c0lorsNmh

OGR2OGR=/usr/bin/ogr2ogr

if [ ! -f $1 ] || [ -z $1 ]; then
	echo "expected a gpx file as param 1"
	exit
fi

SUM=`md5sum $1 | awk '{print $1}'`

$OGR2OGR \
-f "MySQL" \
MYSQL:"$DBASE,user=$USER,password=$PASSWD" \
-lco engine=MYISAM \
-nln TRACK_$SUM \
-overwrite \
$1
