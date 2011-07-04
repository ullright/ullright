<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new myTestCase(6, new lime_output_color, $configuration);
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
