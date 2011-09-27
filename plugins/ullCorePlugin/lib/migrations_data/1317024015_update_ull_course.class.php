<?php

class UpdateUllCourse extends Doctrine_Migration_Base
{
  public function up()
  {
    // Fix course status names
    $status = Doctrine::getTable('UllCourseStatus')->findOneBySlug('insufficient-participants');
    
    $status->Translation['en']->name = 'Spots available';
    $status->Translation['de']->name = 'Plätze frei';
    $status->slug = 'spots-available';
    
    $status->save();
    
    
    $status = Doctrine::getTable('UllCourseStatus')->findOneBySlug('announced');
    
    $status->Translation['en']->name = 'Fully booked';
    $status->Translation['de']->name = 'Augebucht';
    $status->slug = 'fully-booked';
    
    $status->save();    
    
    
    // Refresh all courses to update status etc
    $courses = Doctrine::getTable('UllCourse')->findAll();
    
    foreach ($courses as $course)
    {
      $course->save();
    }
    
    
    
    
  }

  public function down()
  {
  }
}
