<?php

include dirname(__FILE__) . '/../../../../test/bootstrap/unit.php';

class myTestCase extends sfDoctrineTestCase
{
}

// create context since it is required by ->getUser() etc.
sfContext::createInstance($configuration);

$t = new myTestCase(25, new lime_output_color, $configuration);
$path = dirname(__FILE__);
$t->setFixturesPath($path);

$t->begin('updatePriceNegotiated()');

  $booking = newBooking();
  $booking->save();
  $t->is($booking->price_negotiated, 239.90, 'Sets the tariff price as default');
  
  $booking = newBooking();
  $booking->price_negotiated = 99.99;
  $booking->save();
  $t->is($booking->price_negotiated, 99.99, 'Sets the correct tariff price upon insert');  
  
  $booking->price_negotiated = 999.99;
  $booking->save();
  $t->is($booking->price_negotiated, 999.99, 'Sets the correct tariff price upon update'); 

$t->diag('updatePaidAt()');

  $booking = newBooking();
  $booking->is_paid = true;
  $booking->save();
  
  $t->is(substr($booking->paid_at, 0, 10), date('Y-m-d'), 'Sets the correct paid_at datetime');
  
  $booking->is_paid = false;
  $booking->save();
  $t->is($booking->paid_at, null, 'Removes the paid_at date');

$t->diag('updatePricePaid()');

  $booking = newBooking();
  $booking->save();
  $t->is($booking->price_paid, null, 'Does nothing when not paid');
  
  $booking->is_paid = true;
  $booking->save();
  $t->is($booking->price_paid, 239.90, 'Sets the tariff price as default upon update');

  $booking = newBooking();
  $booking->is_paid = true;
  $booking->save();
  $t->is($booking->price_paid, 239.90, 'Sets the tariff price as default upon insert');
  
  $booking = newBooking();
  $booking->is_paid = true;
  $booking->price_paid = 99.99;
  $booking->save();
  $t->is($booking->price_paid, 99.99, 'Ignores a manual price_paid upon insert');
  
  $booking->price_paid = 999.99;
  $booking->save();
  $t->is($booking->price_paid, 999.99, 'Ignores a manual price_paid upon update');  

  
$t->diag('validateTarif()');

  $booking = newBooking();
  $booking->save();
  
  $t->pass('UllCourseBooking can be saved with a valid tariff for the course');
  
  $booking = newBooking(1, 1, 4);
  try
  {
    $booking->save();
    $t->fail('UllCourseBooking does not throw an exception upon saving a invalid tariff for the course');
  }
  catch (Exception $e)
  {
    $t->pass('UllCourseBooking throws an exception upon saving a invalid tariff for the course');
  }
  
  
$t->begin('updateStatus()');

  $booking = Doctrine::getTable('UllCourseBooking')->findOneById(1);
  $t->is($booking->UllCourseBookingStatus->slug, 'paid', 'Sets correct status paid');
  
  $booking = Doctrine::getTable('UllCourseBooking')->findOneById(3);
  $t->is($booking->UllCourseBookingStatus->slug, 'booked', 'Sets correct status booked');

  $booking->is_active = false;
  $booking->save();
  $t->is($booking->UllCourseBookingStatus->slug, 'deleted', 'Sets correct status deleted');  
  
  $booking = Doctrine::getTable('UllCourseBooking')->findOneById(1);
  $booking->price_paid = 1;
  $booking->save();
  $t->is($booking->UllCourseBookingStatus->slug, 'underpaid', 'Sets correct status underpaid');
  
  $booking = Doctrine::getTable('UllCourseBooking')->findOneById(1);
  $booking->price_paid = 1000;
  $booking->save();
  $t->is($booking->UllCourseBookingStatus->slug, 'overpaid', 'Sets correct status overpaid'); 

  $booking = Doctrine::getTable('UllCourseBooking')->findOneById(2);
  $booking->is_supernumerary_paid  = true;
  $booking->price_paid = 159.90;
  $booking->save();
  $t->is($booking->UllCourseBookingStatus->slug, 'supernumerary-paid', 'Sets correct status supernumerary-paid');  
  
  $booking = Doctrine::getTable('UllCourseBooking')->findOneById(4);
  $booking->is_supernumerary_booked  = true;
  $booking->save();
  $t->is($booking->UllCourseBookingStatus->slug, 'supernumerary-booked', 'Sets correct status supernumerary-booked');  


$t->begin('update course proxies (implicitly call to UllCourseBooking post save hooks)');

  // We test the objects created by the fixtures here
  $course1 = Doctrine::getTable('UllCourse')->findOneById(1);
  $course2 = Doctrine::getTable('UllCourse')->findOneById(2);

  $t->is($course1->proxy_number_of_participants_applied, 3, 'Calculates correct number of participants who applied for course 1');
  $t->is($course1->proxy_number_of_participants_paid, 2, 'Calculates correct number of participants who paid for course 1');
  $t->is($course1->proxy_turnover, 399.80, 'Calculates correct turnover for course 1');
  
  $t->is($course2->proxy_number_of_participants_applied, 1, 'Calculates correct number of Participants for course 2');
  $t->is($course2->proxy_number_of_participants_paid, 0, 'Calculates correct number of participants who paid for course 2');
  $t->is($course2->proxy_turnover, 0.00, 'Calculates correct turnover for course 2');  
  
  
function newBooking($courseId = 1, $userId = 1, $tariffId = 1)
{
  $course = Doctrine::getTable('UllCourse')->findOneById($courseId);
  $user = Doctrine::getTable('UllUser')->findOneById($userId);
  $tarif = Doctrine::getTable('UllCourseTariff')->findOneById($tariffId);

  $booking = new UllCourseBooking;
  $booking['UllCourse'] = $course;
  $booking['UllUser'] = $user;
  $booking['UllCourseTariff'] = $tarif;

  return $booking;
}  