<?php

/**
 * PluginUllCourseBookingTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PluginUllCourseBookingTable extends UllRecordTable
{
  /**
   * Returns an instance of this class.
   *
   * @return object PluginUllCourseBookingTable
   */
  public static function getInstance()
  {
    return Doctrine_Core::getTable('PluginUllCourseBooking');
  }
    
  /**
   * Check if a booking has a valid tariff
   * 
   * @param UllCourseBooking $booking
   */
  public static function validateTarif(UllCourseBooking $booking)
  {
    $validTariffs = UllCourseTariffTable::findIdsByCourseId($booking->UllCourse->id);
    
    if (!in_array($booking->UllCourseTariff->id, $validTariffs))
    {
      throw new InvalidArgumentException('Invalid tariff given');
    }
  }      
  
  /**
   * Find by course, orderd by UllUser->last_name_first
   * 
   * @param integer $ull_course_id
   * @return Doctrine_Collection
   */
  public static function findByCourseOrderedByUserName($ull_course_id)
  {
    $q = new Doctrine_Query;
    
    $q
      ->from('UllCourseBooking b, b.UllUser u')
      ->where('b.ull_course_id = ?', $ull_course_id)
      ->orderby('u.last_name_first, b.created_at, b.id')
    ;
    
    return $q->execute();
  }
  
  /**
   * Mark supernumerary bookings
   * 
   * @param integer $ull_course_id
   */
  public static function updateSupernumerary($ull_course_id)
  {
    $course = Doctrine::getTable('UllCourse')->findOneById($ull_course_id);
    
    // mark supernumerary bookings
    $bookings = self::findBookingsByCourseOrderedByBookingDate($ull_course_id);
    
    $i = 1;
    foreach($bookings as $booking)
    {
      if ($i > $course->max_number_of_participants)
      {
        $booking->is_supernumerary_booked = true;
        $booking->save();
      }
      
      $i++;
    }
    
    // mark supernumerary paid bookings
    $paidBookings = self::findPaidBookingsByCourseOrderedByPaidDate($ull_course_id);
    
    $i = 1;
    foreach($paidBookings as $paidBooking)
    {
      if ($i > $course->max_number_of_participants)
      {
        $paidBooking->is_supernumerary_paid = true;
        $paidBooking->save();
      }
      
      $i++;
    }    
  }

  /**
   * Find all bookings for a course which are not deleted and not paid yet
   * ordered by booking (=creation) date
   * 
   * @param id $ull_course_id
   */
  public static function findBookingsByCourseOrderedByBookingDate($ull_course_id)
  {
    $q = new Doctrine_Query;
    
    $q
      ->from('UllCourseBooking b')
      ->where('b.ull_course_id = ?', $ull_course_id)
      ->addWhere('b.is_paid = ?', false)
      ->addWhere('b.is_active = ?', true)
      ->orderBy('b.created_at, b.id')
    ;
    
    $result = $q->execute();
    
    return $result;
  }
  
  /**
   * Find all paid bookings for a course which are not deleted
   * ordered by pay date
   * 
   * @param id $ull_course_id
   */
  public static function findPaidBookingsByCourseOrderedByPaidDate($ull_course_id)
  {
    $q = new Doctrine_Query;
    
    $q
      ->from('UllCourseBooking b')
      ->where('b.ull_course_id = ?', $ull_course_id)
      ->addWhere('is_paid = ?', true)
      ->addWhere('is_active = ?', true)
      ->orderBy('b.paid_at, b.id')
    ;
    
    return $q->execute();    
  }

  
  /**
   * Listen to the ull_table_tool.update_single_column event
   * 
   * If "is_paid" was updated, check if we need to send a confirmation mail  
   *  
   * @param sfEvent $event
   */
  public static function listenToUpdateSingleColumnEvent(sfEvent $event)
  {
    $params = $event->getParameters();
    
    $column = $params['column'];
    $booking = $params['object'];
    
    // Send payment received email if its no supernumerary booking
    if ('is_paid' == $column && $booking->shouldWeSendPaymentReceivedMail())
    {
      $booking->sendPaymentReceivedMail();
    }    
  }
}
