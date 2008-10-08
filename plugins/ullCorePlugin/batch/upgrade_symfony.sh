#!/bin/bash
#
# Upgrades symfony to the latest version

REPO_URL="http://svn.symfony-project.com/tags/RELEASE_1_1_4/lib"
LOCAL_PATH="vendor/symfony/lib"

svn update $LOCAL_PATH
svn rm $LOCAL_PATH
svn ci $LOCAL_PATH -m 'updated symfony (delete)'
svn export $REPO_URL $LOCAL_PATH
svn add $LOCAL_PATH
svn ci $LOCAL_PATH -m 'updated symfony'

echo Done!