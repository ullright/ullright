<?php

class AddUllCourseData extends Doctrine_Migration_Base
{
  public function up()
  {
    RecreateForeignKeysTask::createAllForeignKeysFromModel();
  }
  
  public function postUp()
  {
    // Load the ullCourse base data only if the module is enabled
    $enabledModules = sfConfig::get('sf_enabled_modules');
    
    if (in_array('ullCourse', $enabledModules))
    {
      echo shell_exec('php symfony ull_course:load-ull-course-base-data');
    }
        
  }
  
  public function down()
  {
  }
}
