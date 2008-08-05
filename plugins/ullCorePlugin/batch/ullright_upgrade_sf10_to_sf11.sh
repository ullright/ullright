#!/bin/bash
#

REPO_URL="https://ssl.ull.at/svn/ullright/trunk"

echo "Removing old config files..."
rm config/i18n.yml
rm config/config.php
rm apps/myApp/config/i18n.yml
rm apps/myApp/config/logging.yml
rm apps/myApp/config/config.php

echo ""
echo "Getting new config files from ullright repository"

MYPATH="symfony"
svn export $REPO_URL/$MYPATH $MYPATH

MYPATH="config/ProjectConfiguration.class.php"
svn export $REPO_URL/$MYPATH $MYPATH

MYPATH="apps/myApp/config/settings.yml"
svn export $REPO_URL/$MYPATH $MYPATH

MYPATH="apps/myApp/config/factories.yml"
svn export $REPO_URL/$MYPATH $MYPATH

echo ""
echo "running symfony project:upgrade1.1 task"
php symfony project:upgrade1.1

echo Done!