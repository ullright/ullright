<?php
/**
 * TableConfiguration for UllUser
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class BaseUllEntityTableConfiguration extends ullTableConfiguration
{
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
    $this
      ->setName(__('Users', null, 'ullCoreMessages'))
      ->setSearchColumns(array('id', 'display_name', 'username', 'email', 'UllLocation->name', 'UllDepartment->name'))
      ->setOrderBy('last_name, first_name')
      ->setListColumns(array('id', 'first_name', 'last_name', 'username', 'email', 'UllLocation->name', 'UllDepartment->name'))
      ->setForeignRelationName(__('User', null, 'ullCoreMessages'))
    ;
  }
  
}