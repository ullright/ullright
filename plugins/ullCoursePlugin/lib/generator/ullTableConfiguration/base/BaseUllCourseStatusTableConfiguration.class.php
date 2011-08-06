<?php
/**
 * TableConfiguration
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class BaseUllCourseStatusTableConfiguration extends ullTableConfiguration
{
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
    $this
      ->setName(__('Course status', null, 'common'))
      ->setSearchColumns(array(
        'name', 
      ))
      ->setOrderBy('name')
      ->setListColumns(array(
        'name',
      ))
      ->setForeignRelationName(__('Status', null, 'common'))
//      ->setToStringColumn('display_name');
    ;
  }
  
}