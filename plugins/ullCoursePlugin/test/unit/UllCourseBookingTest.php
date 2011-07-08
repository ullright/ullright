<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new myTestCase(12, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);


$t->begin('update course proxies (implicitly call UllCourseBooking post save hooks)');

  // We test the objects created by the fixtures here!

  $course1 = Doctrine::getTable('UllCourse')->findOneById(1);
  $course2 = Doctrine::getTable('UllCourse')->findOneById(2);

  $t->is($course1->proxy_number_of_participants_applied, 3, 'Calculates correct number of participants who applied for course 1');
  $t->is($course1->proxy_number_of_participants_paid, 2, 'Calculates correct number of participants who paid for course 1');
  $t->is($course1->proxy_turnover, 399.80, 'Calculates correct turnover for course 1');
  
  $t->is($course2->proxy_number_of_participants_applied, 1, 'Calculates correct number of Participants for course 2');
  $t->is($course2->proxy_number_of_participants_paid, 0, 'Calculates correct number of participants who paid for course 2');
  $t->is($course2->proxy_turnover, 0.00, 'Calculates correct turnover for course 2');

$t->diag('is_paid triggers insert of marked_as_paid_by and _at');

  $booking1 = Doctrine::getTable('UllCourseBooking')->findOneById(1);
  $t->is($booking1['marked_as_paid_ull_user_id'], 1, 'marked_as_paid_ull_user_id is correctly filled in');
  $t->is(substr($booking1['marked_as_paid_at'], 0, 10), date('Y-m-d'), 'marked_as_paid_at is correctly filled in');
  
  $booking2 = Doctrine::getTable('UllCourseBooking')->findOneById(2);
  $t->is($booking2['marked_as_paid_ull_user_id'], 1, 'marked_as_paid_ull_user_id is correctly filled in');
  $t->is(substr($booking2['marked_as_paid_at'], 0, 10), date('Y-m-d'), 'marked_as_paid_at is correctly filled in');

  $booking3 = Doctrine::getTable('UllCourseBooking')->findOneById(3);
  $t->is($booking3['marked_as_paid_ull_user_id'], null, 'marked_as_paid_ull_user_id is empty');
  $t->is($booking3['marked_as_paid_at'], null, 'marked_as_paid_at is empty');  