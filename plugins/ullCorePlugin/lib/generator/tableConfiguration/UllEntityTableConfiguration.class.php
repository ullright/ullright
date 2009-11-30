<?php
/**
 * TableConfiguration for UllUser
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class UllEntityTableConfiguration extends ullTableConfiguration
{
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
    $this
      ->setName(__('Users', null, 'ullCoreMessages'))
      ->setSearchColumns(array('display_name', 'username', 'email', 'UllLocation->name', 'UllDepartment->name'))
      ->setOrderBy('last_name, first_name')
      ->setListColumns(array('id', 'first_name', 'last_name', 'username', 'email', 'UllLocation->name', 'UllDepartment->name'))
      // We don't need to set the foreign relation names here, because ullHumanizer does the job
//      ->setForeignRelationName(__('User', null, 'ullCoreMessages'))
    ;
  }
  
}