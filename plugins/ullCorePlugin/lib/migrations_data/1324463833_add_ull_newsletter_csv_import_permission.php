<?php

class AddUllUllNewsletterCsvImportPermission extends Doctrine_Migration_Base
{
  public function up()
  {
    $permission = new UllPermission;
    $permission['namespace'] = 'ull_newsletter';
    $permission['slug'] = 'ull_newsletter_csv_import';
    $permission->save();
    
    $admins = UllGroupTable::findByDisplayName('NewsletterAdmins');
    
    $group_permission = new UllGroupPermission;
    $group_permission['namespace'] = 'ull_newsletter';
    $group_permission['UllGroup'] = $admins;
    $group_permission['UllPermission'] = $permission;
    $group_permission->save();
  }

  public function down()
  {
  }
}
