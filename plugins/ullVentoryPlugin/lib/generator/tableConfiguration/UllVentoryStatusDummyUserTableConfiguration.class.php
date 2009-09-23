<?php
/**
 * TableConfiguration for UllVentoryStatusDummyUser
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class UllVentoryStatusDummyUserTableConfiguration extends ullTableConfiguration
{
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
    $this->setName(__('Status users', null, 'ullVentoryMessages'));
    $this->setSearchColumns(array('username', 'comment'));
    $this->setSortColumns('username');
  }
  
}