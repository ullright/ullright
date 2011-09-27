<?php

class UpdateUllCourse extends Doctrine_Migration_Base
{
  public function up()
  {
    // Refresh all courses to update status etc
    $courses = Doctrine::getTable('UllCourseBooking')->findAll();
    
    foreach ($courses as $course)
    {
      $course->save();
    }
  }

  public function down()
  {
  }
}
