#!/bin/bash
#

PROJECT=`cat config/ull_project_name.txt`
DBPASS=`cat config/ull_db_password.txt`

echo Dumping database...
mysqldump -u $PROJECT --password=$DBPASS --no-create-info --complete-insert $PROJECT > data/fixtures/$PROJECT.mysql.dump

echo Done!