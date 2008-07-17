#!/bin/bash
#

PROJECT=`cat config/ull_project_name.txt`
DBPASS=`cat config/ull_db_password.txt`

echo Loading database...
mysql -u $PROJECT --password=$DBPASS  $PROJECT < data/fixtures/$PROJECT.mysql.dump

echo Done!