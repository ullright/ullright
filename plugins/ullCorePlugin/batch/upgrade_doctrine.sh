#!/bin/bash
#
# Upgrades doctrine to the latest version of the sf1.1 trunk

REPO_URL="http://svn.symfony-project.com/plugins/sfDoctrinePlugin/branches/1.1"

svn update plugins/sfDoctrinePlugin
svn rm plugins/sfDoctrinePlugin
svn ci plugins/sfDoctrinePlugin -m 'updated sfDoctrinePlugin (delete)'
svn export $REPO_URL plugins/sfDoctrinePlugin
svn add plugins/sfDoctrinePlugin
svn ci plugins/sfDoctrinePlugin -m 'updated sfDoctrinePlugin'

echo Done!