#!/bin/bash
#

REPO_URL="https://ssl.ull.at/svn/ullright/trunk"

svn propset svn:externals "sfDoctrinePlugin http://svn.symfony-project.com/plugins/sfDoctrinePlugin/trunk/" plugins
svn update
php symfony cc

echo ""
echo "Getting new config files from ullright repository"

MYPATH="config/databases.yml"
svn export $REPO_URL/$MYPATH $MYPATH

echo Done!