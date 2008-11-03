#!/bin/bash
#
# Upgrades symfony to the latest version

BASE_PATH="vendor/symfony"
REPO_URL="http://svn.symfony-project.com/tags/RELEASE_1_1_4/lib"
LOCAL_PATH="vendor/symfony/lib"
REPO_URL1="http://svn.symfony-project.com/tags/RELEASE_1_1_4/data"
LOCAL_PATH1="vendor/symfony/data"

svn update $LOCAL_PATH
svn update $LOCAL_PATH1
svn rm $BASE_PATH/*
svn ci $BASE_PATH -m 'updated symfony (delete)'
svn export $REPO_URL $LOCAL_PATH
svn export $REPO_URL1 $LOCAL_PATH1
svn add $LOCAL_PATH
svn add $LOCAL_PATH1
svn ci $BASE_PATH -m 'updated symfony'

echo Done!