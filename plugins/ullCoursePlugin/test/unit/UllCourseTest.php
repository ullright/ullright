<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new myTestCase(7, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);


$t->begin('updateStatus())');

  $course = Doctrine::getTable('UllCourse')->findOneById(1);
  
  $t->is($course->UllCourseStatus->slug, 'insufficient-participants', 'Course 1 from the fixtures has status insufficient participants');
  
  $course->proxy_number_of_participants_paid = 11;
  $course->save();
  $t->is($course->UllCourseStatus->slug, 'overbooked', 'Set correct status overbooked');
  
  $course->proxy_number_of_participants_paid = 7;
  $course->save();
  $t->is($course->UllCourseStatus->slug, 'active', 'Set correct status active');    

  $course->begin_date = date('Y-m-d', strtotime('tomorrow'));
  $course->is_active = false;
  $course->save();
  $t->is($course->UllCourseStatus->slug, 'planned', 'Set correct status planned');    
  
  $course->is_active = true;
  $course->save();
  $t->is($course->UllCourseStatus->slug, 'announced', 'Set correct status announced');
  
  $course->end_date = date('Y-m-d', strtotime('yesterday'));
  $course->save();
  $t->is($course->UllCourseStatus->slug, 'finished', 'Set correct status finished');  
  
  $course->is_canceled = true;
  $course->save();
  $t->is($course->UllCourseStatus->slug, 'canceled', 'Set correct status canceled');
  
  
  
  
