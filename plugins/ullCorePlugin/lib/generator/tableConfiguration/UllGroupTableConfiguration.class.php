<?php
/**
 * TableConfiguration for UllGroup
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class UllGroupTableConfiguration extends ullTableConfiguration
{
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
    $this
      ->setName(__('Groups', null, 'ullCoreMessages'))
      ->setSearchColumns(array('display_name', 'email'))
      ->setOrderBy('display_name')
      ->setToStringColumn('display_name')
      ->setForeignRelationName(__('Group', null, 'ullCoreMessages'))
      ->setFilterColumns(array(
        'is_active' => 'checked',
      ))
      ->setListColumns(array(
        'id', 
        'display_name', 
        'email',
        'is_active',
      ))
    ;
  }
  
}