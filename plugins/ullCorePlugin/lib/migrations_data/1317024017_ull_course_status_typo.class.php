<?php

class UllCourseStatusTypo extends Doctrine_Migration_Base
{
  public function up()
  {
    // Load the ullCourse data only if the module is enabled
    $enabledModules = sfConfig::get('sf_enabled_modules');
    
    if (in_array('ullCourse', $enabledModules))
    {
      $status = Doctrine::getTable('UllCourseStatus')->findOneBySlug('fully-booked');
      
      $status->Translation['de']->name = 'Ausgebucht';
      
      $status->save();    
    }
    
  }

  public function down()
  {
  }
}
