<?php
/**
 * TableConfiguration for UllVentoryOriginDummyUser
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class UllVentoryOriginDummyUserTableConfiguration extends ullTableConfiguration
{
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
    $this->setName(__('Origin users', null, 'ullVentoryMessages'));
    $this->setSearchColumns(array('username', 'comment'));
    $this->setSortColumns('username');
  }
  
}