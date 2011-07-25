#!/bin/bash
#

# Cleanup deleted ullFlow model 
svn --force delete lib/model/doctrine/ullFlowPlugin/base/BaseUllFlowAppPermission.class.php
svn --force delete lib/model/doctrine/ullFlowPlugin/UllFlowAppPermission.class.php
svn --force delete lib/model/doctrine/ullFlowPlugin/UllFlowAppPermissionTable.class.php
    
svn --force delete lib/form/doctrine/ullFlowPlugin/base/BaseUllFlowAppPermissionForm.class.php
svn --force delete lib/form/doctrine/ullFlowPlugin/UllFlowAppPermissionForm.class.php
    
svn --force delete lib/filter/doctrine/ullFlowPlugin/base/BaseUllFlowAppPermissionFormFilter.class.php
svn --force delete lib/filter/doctrine/ullFlowPlugin/UllFlowAppPermissionFormFilter.class.php
    
# Create new migration symlinks    
php symfony cache:clear
php symfony doctrine:build --model --forms --filters

cd lib/migrations
ln -s ../../plugins/ullCorePlugin/lib/migrations_data/ data
ln -s ../../plugins/ullCorePlugin/lib/migrations_pre_build_model/ pre_build_model
svn add *
svn ci -m 'Added new migration symlinks'
cd ../..