<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new myTestCase(9, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);


$t->begin('pre save updateStatus())');

  $course = Doctrine::getTable('UllCourse')->findOneById(1);
  
  $t->is($course->UllCourseStatus->slug, 'insufficient-participants', 'Course 1 from the fixtures has status insufficient participants');
  
  $course->proxy_number_of_participants_paid = 11;
  $course->save();
  $t->is($course->UllCourseStatus->slug, 'overbooked', 'Set correct status overbooked');
  
  $course->proxy_number_of_participants_paid = 7;
  $course->save();
  $t->is($course->UllCourseStatus->slug, 'announced', 'Set correct status announced');    

  $course->begin_date = date('Y-m-d', strtotime('tomorrow'));
  $course->is_active = false;
  $course->save();
  $t->is($course->UllCourseStatus->slug, 'planned', 'Set correct status planned');    
  
  $course->is_active = true;
  $course->begin_date = date('Y-m-d', strtotime('yesterday'));
  $course->save();
  $t->is($course->UllCourseStatus->slug, 'active', 'Set correct status active');
  
  $course->end_date = date('Y-m-d', strtotime('yesterday'));
  $course->save();
  $t->is($course->UllCourseStatus->slug, 'finished', 'Set correct status finished');  
  
  $course->is_canceled = true;
  $course->save();
  $t->is($course->UllCourseStatus->slug, 'canceled', 'Set correct status canceled');
  
  
$t->begin('post update updateSupernumeraryBookings()');

  $course = Doctrine::getTable('UllCourse')->findOneById(1);
  $course->max_number_of_participants = 1;
  $course->save();
  
  $bookings = UllCourseBookingTable::findPaidBookingsByCourseOrderedByPaidDate(1);
  $t->is($bookings[0]->is_supernumerary_paid, false, 'Leaves the first paid booking as it was');
  $t->is($bookings[1]->is_supernumerary_paid, true, 'Marks the second paid booking a supernumerary because the max_number_of_pariticpants was changed');
  
  
  
