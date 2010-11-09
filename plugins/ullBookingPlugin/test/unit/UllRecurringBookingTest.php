<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

$t = new myTestCase(33, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);

// create context since it is required by fixBehaviorFields() in ullRecurringBooking
sfContext::createInstance($configuration);

//test invalid combination of duration and recurrence period
$t->begin('createRecurringBooking() with invalid combination of duration and recurrence period');

  $booking = new UllBooking();
  $booking->name = 'TestBooking';
  $booking->ull_booking_resource_id = 2;
  $booking->start = '2020-12-20 10:00:00';
  $booking->end = '2020-12-21 10:00:01';
  
  try
  {
    //daily repeating the booking would cause overlapping bookings
    ullRecurringBooking::createRecurringBooking($booking, 'd', 3);
    $t->fail('Invalid combination of duration and recurrence period NOT caught');
  }
  catch (InvalidArgumentException $e)
  {
    $t->pass('Invalid combination of duration and recurrence period caught');
  }

  
//test invalid recurrent period parameter
$t->diag('createRecurringBooking() with invalid recurrence period');

  $booking->start = '2020-12-20 06:15:00';
  $booking->end = '2020-12-21 03:30:00';
 
  try
  {
    //'b' is not valid ('w' and 'd' is)
    ullRecurringBooking::createRecurringBooking($booking, 'b', 4);
    $t->fail('Invalid recurrence period not caught');
  }
  catch (InvalidArgumentException $e)
  {
    $t->pass('Invalid recurrence period caught');
  }
 
  
//simple recurring booking test
$t->diag('createRecurringBooking() simple');
  
  //three weekly repeats starting in december => test month and year calculations
  ullRecurringBooking::createRecurringBooking($booking, 'w', 3);
  
  $q = Doctrine_Query::create()
    ->from('UllBooking u')
    ->orderBy('u.start')
  ;
  $bookings = $q->execute();
 
  $t->is(count($bookings), 4, 'created the correct amount of bookings');

  $shouldBe = array(
    array('start' => '2020-12-20 06:15:00', 'end' => '2020-12-21 03:30:00'),
    array('start' => '2020-12-27 06:15:00', 'end' => '2020-12-28 03:30:00'),
    array('start' => '2021-01-03 06:15:00', 'end' => '2021-01-04 03:30:00'),
    array('start' => '2021-01-10 06:15:00', 'end' => '2021-01-11 03:30:00'),
  );
  
  $groupName = $bookings[0]->booking_group_name;
  for ($i = 0; $i < 4; $i++)
  {
    $t->is($bookings[$i]->start, $shouldBe[$i]['start'], 'start');
    $t->is($bookings[$i]->end, $shouldBe[$i]['end'], 'end');
    $t->is($bookings[$i]->ull_booking_resource_id, 2, 'booking resource id');
    $t->is($bookings[$i]->name, 'TestBooking', 'name');
    $t->is($bookings[$i]->booking_group_name, $groupName, 'created correct booking');
  }
  
//recurring booking collision test
$t->diag('createRecurringBooking() with collisions');
  
  $booking = new UllBooking();
  $booking->name = 'TestBookingCollision';
  $booking->start = '2021-01-01 22:00:00';
  $booking->end = '2021-01-02 05:45:00';
  $booking->ull_booking_resource_id = 2;

  try
  {
    ullRecurringBooking::createRecurringBooking($booking, 'd', 9);
    $t->fail('Overlapping bookings not caught');
  }
  catch (ullOverlappingBookingException $e)
  {
    $t->pass('Overlapping bookings caught');
    $overlappingBookings = $e->getOverlappingBookings();
    $t->is(count($overlappingBookings), 2, 'detected correct amount of overlapping bookings');
    
    //we know that ullRecurringBooking tries to book in chronological order
    $shouldBe = array(
      array('start' => '2021-01-03 06:15:00', 'end' => '2021-01-04 03:30:00'),
      array('start' => '2021-01-10 06:15:00', 'end' => '2021-01-11 03:30:00'),
    );
    
    for ($i = 0; $i < 2; $i++)
    {
      $t->is($overlappingBookings[$i]->start, $shouldBe[$i]['start'], 'start');
      $t->is($overlappingBookings[$i]->end, $shouldBe[$i]['end'], 'end');
      $t->is($overlappingBookings[$i]->name, 'TestBooking', 'name');
      $t->is($overlappingBookings[$i]->booking_group_name, $groupName,
        'detected a correct overlapping booking');
    }
  }
  
  