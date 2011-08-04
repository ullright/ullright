<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new myTestCase(8, new lime_output_color, $configuration);
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

$t->begin('validateTarif()');

  $course = Doctrine::getTable('UllCourse')->findOneById(1);
  $user = Doctrine::getTable('UllUser')->findOneById(1);
  $tarif = Doctrine::getTable('UllCourseTariff')->findOneById(1);

  $booking = new UllCourseBooking;
  $booking['UllCourse'] = $course;
  $booking['UllUser'] = $user;
  $booking['UllCourseTariff'] = $tarif;
  $booking->save();
  $t->pass('UllCourseBooking can be saved with a valid tariff for the course');
  
  $tarif = Doctrine::getTable('UllCourseTariff')->findOneById(4);
  $booking = new UllCourseBooking;
  $booking['UllCourse'] = $course;
  $booking['UllUser'] = $user;
  $booking['UllCourseTariff'] = $tarif;
  try
  {
    $booking->save();
    $t->fail('UllCourseBooking does not throw an exception upon saving a invalid tariff for the course');
  }
  catch (Exception $e)
  {
    $t->pass('UllCourseBooking throws an exception upon saving a invalid tariff for the course');
  }
  
  
  
  
