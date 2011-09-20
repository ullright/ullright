<?php

class RefactorUllUserPermissions extends Doctrine_Migration_Base
{
  public function up()
  {
    $admins = UllGroupTable::findByDisplayName('MasterAdmins');
    $userAdmins = UllGroupTable::findByDisplayName('UserAdmins');
    
    $permission = new UllPermission();
    $permission->slug = 'ull_admin_index';
    $permission->namespace = 'ull_core';
    $permission->save();
    
    $groupPermission = new UllGroupPermission();
    $groupPermission->UllGroup = $admins;
    $groupPermission->UllPermission = $permission;
    $groupPermission->namespace = 'ull_core';
    $groupPermission->save();
    
    $groupPermission = new UllGroupPermission();
    $groupPermission->UllGroup = $userAdmins;
    $groupPermission->UllPermission = $permission;
    $groupPermission->namespace = 'ull_core';
    $groupPermission->save();    

    
    $permission = new UllPermission();
    $permission->slug = 'ull_user_list';
    $permission->namespace = 'ull_core';
    $permission->save();
    
    $groupPermission = new UllGroupPermission();
    $groupPermission->UllGroup = $admins;
    $groupPermission->UllPermission = $permission;
    $groupPermission->namespace = 'ull_core';
    $groupPermission->save();
    
    $groupPermission = new UllGroupPermission();
    $groupPermission->UllGroup = $userAdmins;
    $groupPermission->UllPermission = $permission;
    $groupPermission->namespace = 'ull_core';
    $groupPermission->save();     
    
    
    $permission = new UllPermission();
    $permission->slug = 'ull_user_edit';
    $permission->namespace = 'ull_core';
    $permission->save();
    
    $groupPermission = new UllGroupPermission();
    $groupPermission->UllGroup = $admins;
    $groupPermission->UllPermission = $permission;
    $groupPermission->namespace = 'ull_core';
    $groupPermission->save();
    
    $groupPermission = new UllGroupPermission();
    $groupPermission->UllGroup = $userAdmins;
    $groupPermission->UllPermission = $permission;
    $groupPermission->namespace = 'ull_core';
    $groupPermission->save();  

    
    $permission = new UllPermission();
    $permission->slug = 'ull_user_delete';
    $permission->namespace = 'ull_core';
    $permission->save();
    
    $groupPermission = new UllGroupPermission();
    $groupPermission->UllGroup = $admins;
    $groupPermission->UllPermission = $permission;
    $groupPermission->namespace = 'ull_core';
    $groupPermission->save();
    
    
    $permission = new UllPermission();
    $permission->slug = 'ull_user_delete_future_version';
    $permission->namespace = 'ull_core';
    $permission->save();
    
    $groupPermission = new UllGroupPermission();
    $groupPermission->UllGroup = $admins;
    $groupPermission->UllPermission = $permission;
    $groupPermission->namespace = 'ull_core';
    $groupPermission->save();    
    
    
  }

  public function down()
  {
  }
}
