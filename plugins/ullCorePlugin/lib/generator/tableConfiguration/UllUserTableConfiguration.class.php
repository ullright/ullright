<?php
/**
 * TableConfiguration for UllUser
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class UllUserTableConfiguration extends ullTableConfiguration
{
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
    $this->setName(__('Users', null, 'ullCoreMessages'));
    $this->setSearchColumns(array('display_name', 'username', 'email'));
    $this->setSortColumns('last_name, first_name');
  }
  
}