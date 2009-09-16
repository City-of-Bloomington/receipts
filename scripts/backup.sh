#!/bin/bash
# Shell script to create database dumps
# Purges old database dumps to save space
#
# @copyright Copyright 2009 City of Bloomington, Indiana
# @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.txt
# @author Cliff Ingham <inghamn@bloomington.in.gov>
#
BACKUP_DIR=/var/www/backups/receipt
MYSQLDUMP=/usr/local/mysql/bin/mysqldump

MYSQL_DBNAME=receipt
MYSQL_USER=username
MYSQL_PASS=password

# How many days worth of backups to keep around
num_days_to_keep=5

#----------------------------------------------------------
# No Editing Required below this line
#----------------------------------------------------------
now=`date +%s`
today=`date +%F`

cd $BACKUP_DIR

# Dump the database
$MYSQLDUMP -u $MYSQL_USER -p$MYSQL_PASS $MYSQL_DBNAME > $today.sql

# Purge any backup tarballs that are too old
for file in `ls`
do
	atime=`stat -c %Y $file`
	if [ $(( $now - $atime >= $num_days_to_keep*24*60*60 )) = 1 ]
	then
		rm $file
	fi
done
