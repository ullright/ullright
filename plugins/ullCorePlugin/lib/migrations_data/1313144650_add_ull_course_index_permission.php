<?php

class AddUllCourseIndexPermission extends Doctrine_Migration_Base
{
  public function up()
  {
    // Load the ullCourse data only if the module is enabled
    $enabledModules = sfConfig::get('sf_enabled_modules');
    
    if (in_array('ullCourse', $enabledModules))
    {
      $permission = new UllPermission;
      $permission['namespace'] = 'ull_course';
      $permission['slug'] = 'ull_course_index';
      $permission->save();
      $ull_permission_ull_course_index = $permission;   
      
      $ull_group_ull_course_admins = UllGroupTable::findByDisplayName('CourseAdmins');
      
      $group_permission = new UllGroupPermission;
      $group_permission['namespace'] = 'ull_course';
      $group_permission['UllGroup'] = $ull_group_ull_course_admins;
      $group_permission['UllPermission'] = $ull_permission_ull_course_index;
      $group_permission->save();        
      
      
    }    
  }

  public function down()
  {
  }
}
