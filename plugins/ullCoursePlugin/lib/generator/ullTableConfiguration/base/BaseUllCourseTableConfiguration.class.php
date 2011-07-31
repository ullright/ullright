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
      ->setSearchColumns(array('id', 'name'))
      ->setOrderBy('sequence, name')
      ->setListColumns(array(
        'id', 
        'name', 
        'trainer_ull_user_id', 
        'begin_date', 
        'is_active',
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