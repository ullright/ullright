<?php
/**
 * TableConfiguration
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class BaseUllCourseTableConfiguration extends ullTableConfiguration
{
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
    $this
      ->setName(__('Courses', null, 'ullCourseMessages'))
      ->setSearchColumns(array(
        'id', 
        'name', 
        'duplicate_tags_for_search')
      )
      ->setOrderBy('name')
      ->setListColumns(array(
        'link_to_bookings',
        'id', 
        'name', 
        'trainer_ull_user_id',
        'duplicate_tags_for_search', 
        'begin_date', 
        'is_active',
        'min_number_of_participants',
        'proxy_number_of_participants_applied',
        'proxy_number_of_participants_paid',
        'proxy_turnover',      
      ))
      ->setForeignRelationName(__('Course', null, 'ullCourseMessages'))
//      ->setToStringColumn('display_name');
      ->setFilterColumns(array(
        'trainer_ull_user_id' => '',
        'is_active' => 'checked',
      ))
    ;
  }
  
}