<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new myTestCase(0, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);


//$t->begin('updateSupernumerary()');
//
//  $booking = Doctrine::getTable('UllCourseBooking')->findOneById(1);
//  $t->is($booking->is_supernumerary_booked, false, 'is_supernumerary_booked is correctly set to false for course 1');
//  $t->is($booking->is_supernumerary_paid, false, 'is_supernumerary_paid is correctly set to false for course 1');
//  
//  $booking = Doctrine::getTable('UllCourseBooking')->findOneById(3);
//  $t->is($booking->is_supernumerary_booked, false, 'is_supernumerary_booked is correctly set to false for course 3');  
//  $t->is($booking->is_supernumerary_paid, false, 'is_supernumerary_paid is correctly set to false for course 3');
//
//  // course 1 participants: min = 6; max = 10, currently 2 paid, 1 booked
//  $course = Doctrine::getTable('UllCourse')->findOneById(1);
//  $user = Doctrine::getTable('UllUser')->findOneById(1);
//  $tarif = Doctrine::getTable('UllCourseTariff')->findOneById(1);
//
//  // create 10 bookings
//  $booking = new UllCourseBooking;
//  $booking['UllCourse'] = $course;
//  $booking['UllUser'] = $user;
//  $booking['UllCourseTariff'] = $tarif;
//  
//  for ($i = 1; $i <= 10; $i++)
//  {
//    $currentBooking = clone $booking;
//    $booking->save();
//  }  
//  
//  $t->is($booking)
  
  
