<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new myTestCase(15, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);

$t->begin('findBookingsByCourseOrderedByBookingDate()');
  $bookings = UllCourseBookingTable::findBookingsByCourseOrderedByBookingDate(1);
  $t->is(count($bookings), 1, 'Returns 1 booking');
  $t->is($bookings[0]->id, 3, 'Returns the correct booking');
  
$t->diag('findPaidBookingsByCourseOrderedByPaidDate()');
  $bookings = UllCourseBookingTable::findPaidBookingsByCourseOrderedByPaidDate(1);
  $t->is(count($bookings), 2, 'Returns 2 paid bookings');
  $t->is($bookings[0]->id, 1, 'Returns the correct booking');  
  $t->is($bookings[1]->id, 2, 'Returns the correct booking');

$t->diag('updateSupernumerary()');

  $booking = Doctrine::getTable('UllCourseBooking')->findOneById(1);
  $t->is($booking->is_supernumerary_booked, false, 'is_supernumerary_booked is correctly set to false for course 1');
  $t->is($booking->is_supernumerary_paid, false, 'is_supernumerary_paid is correctly set to false for course 1');
  
  $booking = Doctrine::getTable('UllCourseBooking')->findOneById(3);
  $t->is($booking->is_supernumerary_booked, false, 'is_supernumerary_booked is correctly set to false for course 3');  
  $t->is($booking->is_supernumerary_paid, false, 'is_supernumerary_paid is correctly set to false for course 3');

  // course 1 participants: min = 6; max = 10, currently 2 paid, 1 booked
  $course = Doctrine::getTable('UllCourse')->findOneById(1);
  $user = Doctrine::getTable('UllUser')->findOneById(1);
  $tarif = Doctrine::getTable('UllCourseTariff')->findOneById(1);

  // create 10 bookings
  $booking = new UllCourseBooking;
  $booking['UllCourse'] = $course;
  $booking['UllUser'] = $user;
  $booking['UllCourseTariff'] = $tarif;
  
  for ($i = 1; $i <= 10; $i++)
  {
    $currentBooking = clone $booking;
    $currentBooking->save();
  }  
  
  $bookings = UllCourseBookingTable::findBookingsByCourseOrderedByBookingDate(1);
  
  $t->is($bookings[0]->is_supernumerary_booked, false, 'The first booking is not marked as supernumerary');
  $t->is($bookings[9]->is_supernumerary_booked, false, 'The 10th booking is not marked as supernumerary');  
  $t->is($bookings[10]->is_supernumerary_booked, true, 'The 11th booking is marked as supernumerary');  
  
  // create 9 paid bookings
  $booking = new UllCourseBooking;
  $booking['UllCourse'] = $course;
  $booking['UllUser'] = $user;
  $booking['UllCourseTariff'] = $tarif;
  $booking['is_paid'] = true;
  
  for ($i = 1; $i <= 9; $i++)
  {
    $currentBooking = clone $booking;
    $currentBooking->save();
  }    

  $bookings = UllCourseBookingTable::findPaidBookingsByCourseOrderedByPaidDate(1);
  
  $t->is($bookings[0]->is_supernumerary_paid, false, 'The first booking is not marked as supernumerary paid');
  $t->is($bookings[9]->is_supernumerary_paid, false, 'The 10th booking is not marked as supernumerary paid');  
  $t->is($bookings[10]->is_supernumerary_paid, true, 'The 11th booking is marked as supernumerary paid');  
  
  
  
  
  

  