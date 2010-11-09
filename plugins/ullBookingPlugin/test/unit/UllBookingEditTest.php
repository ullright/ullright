<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

$t = new myTestCase(2, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);

//test single booking edit
$t->begin('edit of a single booking');

  //insert two test bookings
  $booking = new UllBooking();
  $booking->name = 'TestBooking';
  $booking->ull_booking_resource_id = 2;
  $booking->start = '2020-12-20 10:00:00';
  $booking->end = '2020-12-20 11:00:00';
  $booking->save();
  
  $booking2 = new UllBooking();
  $booking2->name = 'TestBooking2';
  $booking2->ull_booking_resource_id = 2;
  $booking2->start = '2020-12-25 8:00:00';
  $booking2->end = '2020-12-25 12:00:00';
  $booking2->save();

  // modify the first booking
  $booking->name = 'Modified TestBooking';
  $booking->end = '2020-12-20 12:30:00';
  //this automatically tests overlap check
  //exemption of the booking's id
  $booking->save();
  
  $bookings = Doctrine::getTable('UllBooking')->findAll();
  $t->is(count($bookings), 2, 'amount of bookings has not changed');

//test invalid (overlapping) edit of a single booking
$t->diag('invalid edit of a single booking');
  
  $booking->end = '2020-12-25 09:00:00';
  
  try
  {
    $booking->save();
    $t->fail('Overlapping booking not caught');
  }
  catch (ullOverlappingBookingException $e)
  {
    $t->pass('Overlapping booking caught');
  }

  