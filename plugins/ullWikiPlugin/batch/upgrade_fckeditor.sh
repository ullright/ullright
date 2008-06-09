#!/bin/bash
#
# upgrade script for fckeditor used in ullWikiPlugin
# klemens.ullmann@ull.at 2008-05-28
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



echo Downloading fckeditor and extracting...

cd plugins/ullWikiPlugin/web/js
svn mv fckeditor fckeditor.${DATE}.bkp
wget $1
FILES=`find -name FCKeditor*.tar.gz`

for f in $FILES; do
  tar -xvzf $f
  rm $f
done

echo Removing unnecessary files...
chmod u+w * -R
find . -name '_*' -exec rm -rf {} \;







