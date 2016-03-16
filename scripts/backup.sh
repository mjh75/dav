#!/bin/bash
#
# Change to the directory provided as the first argument then do a mysqldump
# of the dav database into a file named dav-<current date>.sql
#
cd $1
d=$(/bin/date "+%Y-%m-%d-%H:%M")
/usr/bin/mysqldump dav -u root --complete-insert --lock-all-tables > dav-$d.sql