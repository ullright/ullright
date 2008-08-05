#!/bin/bash
#

REPO_URL="https://ssl.ull.at/svn/ullright/trunk"

echo "Removing old config files..."
rm config/i18n.yml
rm apps/myApp/config/i18n.yml
rm apps/myApp/config/logging.yml

echo ""
echo "Getting new config files from ullright repository"

MYPATH="config/ProjectConfiguration.class.php"
svn export $REPO_URL/$MYPATH $MYPATH

MYPATH="apps/myApp/config/settings.yml"
svn export $REPO_URL/$MYPATH $MYPATH

MYPATH="apps/myApp/config/factories.yml"
svn export $REPO_URL/$MYPATH $MYPATH

echo Done!