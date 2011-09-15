<?php
/**
 * TableConfiguration
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class BaseUllCourseBookingTableConfiguration extends ullTableConfiguration
{
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
    $this
      ->setName(__('Course bookings', null, 'ullCourseMessages'))
      ->setSearchColumns(array(
        'id', 
        'UllCourse->name', 
        'UllUser->display_name',
        'comment',
      ))
      ->setOrderBy('paid_at, created_at, id')
      ->setListColumns(array(
        'id', 
        'UllCourse->name', 
        'UllUser->display_name',
        'comment',
//        'created_at',
//        'is_approved', 
        'UllCourseBookingStatus->name',
        'is_paid',
//        'price_negotiated',
        'price_paid',
         
        
        'paid_at',
        'UllCourse->is_active',        
      ))
      ->setForeignRelationName(__('User', null, 'ullCoreMessages'))
//      ->setToStringColumn('display_name');
      ->setFilterColumns(array(
        'ull_course_id' => '',
//        'is_approved' => false,
        'is_paid' => false,
        'ull_course_booking_status_id' => '',
        'UllCourse->is_active' => 'checked'
      ))
    ;
  }
  
}