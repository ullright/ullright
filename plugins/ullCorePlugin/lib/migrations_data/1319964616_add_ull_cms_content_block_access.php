<?php

class AddUllCmsContentBlockAccess extends Doctrine_Migration_Base
{
  public function up()
  {
    $admins = UllGroupTable::findByDisplayName('CmsAdmins');
    $slugPrefix = 'ull_cms_content_block_';
    $namespace = 'ull_cms';

    
    $actions = array(
      'list',
      'edit',
      'delete',
    );
    
    foreach ($actions as $action)
    {
      $permission = new UllPermission();
      $permission->slug = $slugPrefix . $action;
      $permission->namespace = $namespace;
      $permission->save();
      
      $groupPermission = new UllGroupPermission();
      $groupPermission->UllGroup = $admins;
      $groupPermission->UllPermission = $permission;
      $groupPermission->namespace = $namespace;
      $groupPermission->save();      
    }
    
  }

  public function down()
  {
  }
}
