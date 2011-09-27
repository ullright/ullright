<?php

class UpdateUllCourse extends Doctrine_Migration_Base
{
  public function up()
  {
    // Load the ullCourse data only if the module is enabled
    $enabledModules = sfConfig::get('sf_enabled_modules');
    
    if (in_array('ullCourse', $enabledModules))
    {
      // Refresh all courses to update status etc
      $courses = Doctrine::getTable('UllCourseBooking')->findAll();
      
      foreach ($courses as $course)
      {
        $course->save();
      }
    }
  }

  public function down()
  {
  }
}
