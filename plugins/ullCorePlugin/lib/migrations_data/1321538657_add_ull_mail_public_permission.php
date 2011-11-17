<?php

class AddUllMailPublicPermission extends Doctrine_Migration_Base
{
  public function up()
  {
    $permission = new UllPermission;
    $permission['namespace'] = 'ull_mail';
    $permission['slug'] = 'ull_newsletter_public';
    $permission->save();
    
    $ull_group = UllGroupTable::findByDisplayName('Everyone');
    
    $group_permission = new UllGroupPermission;
    $group_permission['namespace'] = 'ull_mail';
    $group_permission['UllGroup'] = $ull_group;
    $group_permission['UllPermission'] = $permission;
    $group_permission->save();        
  }

  public function down()
  {
  }
}
