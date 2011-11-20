<?php

class AddUllUllMailLogPermission extends Doctrine_Migration_Base
{
  public function up()
  {
    $permission = new UllPermission;
    $permission['namespace'] = 'ull_mail';
    $permission['slug'] = 'ull_mail_log_list';
    $permission->save();
    $ull_permission_list = $permission;

    $permission = new UllPermission;
    $permission['namespace'] = 'ull_mail';
    $permission['slug'] = 'ull_mail_log_edit';
    $permission->save();
    $ull_permission_edit = $permission;       
    
    $admins = UllGroupTable::findByDisplayName('NewsletterAdmins');
    
    $group_permission = new UllGroupPermission;
    $group_permission['namespace'] = 'ull_mail';
    $group_permission['UllGroup'] = $admins;
    $group_permission['UllPermission'] = $ull_permission_list;
    $group_permission->save();

    $group_permission = new UllGroupPermission;
    $group_permission['namespace'] = 'ull_mail';
    $group_permission['UllGroup'] = $admins;
    $group_permission['UllPermission'] = $ull_permission_edit;
    $group_permission->save();     
  }

  public function down()
  {
  }
}
