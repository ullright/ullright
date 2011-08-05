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
}