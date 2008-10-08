#!/bin/bash
#

REPO_URL="https://ssl.ull.at/svn/ullright/trunk"

# the following doesn't work because proset overwrites existing properties
#svn propset svn:externals "sfDoctrinePlugin http://svn.symfony-project.com/plugins/sfDoctrinePlugin/trunk" plugins
#rm -rf plugins/ dkGeshiPlugin
#svn propset svn:externals "dkGeshiPlugin http://svn.symfony-project.com/plugins/dkGeshiPlugin/trunk" plugins
#svn update
#php symfony cc

echo ""
echo "Getting new config files from ullright repository"

MYPATH="config/databases.yml"
svn export $REPO_URL/$MYPATH $MYPATH

MYPATH="config/ProjectConfiguration.class.php"
svn export $REPO_URL/$MYPATH $MYPATH

echo Done!