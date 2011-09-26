<?php

class DefaultUllCourseIsBookable extends Doctrine_Migration_Base
{
  public function up()
  {
    
    // Load the ullCourse data only if the module is enabled
    $enabledModules = sfConfig::get('sf_enabled_modules');
    
    if (in_array('ullCourse', $enabledModules))
    {    
      $q = new Doctrine_Query;
      $q
        ->update('UllCourse c')
        ->set('c.is_bookable', '?', true)
      ;
    
      $num = $q->execute(); //$num = number of updated rows   
    }
    
  }

  public function down()
  {
    
  }
}
