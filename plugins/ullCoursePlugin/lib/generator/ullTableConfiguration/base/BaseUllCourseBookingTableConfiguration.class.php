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
      ->setSearchColumns(array('UllCourse->name', 'UllUser->display_name'))
      ->setOrderBy('created_at desc, name')
      ->setListColumns(array(
        'id', 
        'UllCourse->name', 
        'UllUser->display_name', 
        'is_paid',
        'comment', 
        'created_at',
        'UllCourse->is_active',        
      ))
      ->setForeignRelationName(__('User', null, 'ullCoreMessages'))
//      ->setToStringColumn('display_name');
      ->setFilterColumns(array(
        'ull_course_id' => '',
        // TODO: does not work
//        'is_paid' => '',
        'UllCourse->is_active' => 'checked'
      ))
    ;
  }
  
}