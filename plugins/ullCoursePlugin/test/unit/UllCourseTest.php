<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new myTestCase(19, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);


$t->begin('updateProxies()');

  // We test the objects created by the fixtures here
  $course1 = Doctrine::getTable('UllCourse')->findOneById(1);
  $course2 = Doctrine::getTable('UllCourse')->findOneById(2);

  $t->is($course1->proxy_number_of_participants_applied, 3, 'Calculates correct number of participants who applied for course 1');
  $t->is($course1->proxy_number_of_participants_paid, 2, 'Calculates correct number of participants who paid for course 1');
  $t->is($course1->proxy_turnover, 399.80, 'Calculates correct turnover for course 1');
  
  $t->is($course2->proxy_number_of_participants_applied, 1, 'Calculates correct number of Participants for course 2');
  $t->is($course2->proxy_number_of_participants_paid, 0, 'Calculates correct number of participants who paid for course 2');
  $t->is($course2->proxy_turnover, 0.00, 'Calculates correct turnover for course 2'); 

$t->diag('findRecipients()');

  $recipients = $course1->findRecipients();
  
  $t->is(count($recipients), 2, 'Returns the correct number of recipients');
  $t->is($recipients[0]->username, 'admin', 'Returns the correct users');
  $t->is($recipients[1]->username, 'test_user', 'Returns the correct users');
  
$t->diag('findRecipientsAsArray()');

  $recipients = $course1->findRecipientsAsArray();
  
  $result = array(
    'admin@example.com' => 'Master Admin',
    'test.user@example.com' => 'Test User',
  );
  
  $t->is($recipients, $result, 'Returns the correct recipients');
  
$t->diag('pre save updateStatus())');

  $course = Doctrine::getTable('UllCourse')->findOneById(1);
  
  $t->is($course->UllCourseStatus->slug, 'spots-available', 'Course 1 from the fixtures has status insufficient participants');
  
  $course->proxy_number_of_participants_paid = 11;
  $course->save();
  $t->is($course->UllCourseStatus->slug, 'overbooked', 'Set correct status overbooked');
  
  $course->proxy_number_of_participants_paid = 7;
  $course->save();
  $t->is($course->UllCourseStatus->slug, 'fully-booked', 'Set correct status announced');    

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
  
  
