#!/bin/bash
#
# Upgrade script for fckeditor used in ullWikiPlugin and others
# klemens.ullmann@ull.at 2008-05-28
# klemens.ullmann-marx@ull.at 2010-03-19 
#

DATE=`date +%Y-%m-%d-%H-%M-%S`

if [ $# -lt 1 ] 
then   
  echo "Please provide a fckeditor.tar.gz download URL"
  exit 1
fi

if [ ! -f symfony ] 
then
  echo "This script has to be launched from a symfony project root directory"
  exit 1
fi

cd plugins/ullCorePlugin/web/js

echo Removing old fckeditor
svn rm fckeditor 
svn commit -m "fckeditor update part I"

echo Downloading fckeditor and extracting...
wget $1
tar -xvzf FCKeditor*.tar.gz
rm FCKeditor*.tar.gz

echo Removing unnecessary files...
chmod u+w * -R
find ./fckeditor -name '_*' -exec rm -rf {} \;

echo Restoring config symlinks and svn adding new files
rm fckeditor/editor/filemanager/connectors/php/config.php
svn add fckeditor
ln -s ../../../../../config.php fckeditor/editor/filemanager/connectors/php/config.php
svn add fckeditor/editor/filemanager/connectors/php/config.php
ln -s ../../../fckeditor_plugin_syntaxhighlight2 fckeditor/editor/plugins/syntaxhighlight2
svn add fckeditor/editor/plugins/syntaxhighlight2

echo Commiting...
svn commit -m "fckeditor update part II"

cd ../../../../




