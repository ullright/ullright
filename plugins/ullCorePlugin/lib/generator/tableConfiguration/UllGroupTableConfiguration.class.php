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
    $this->setName(__('Groups', null, 'ullCoreMessages'));
    $this->setSearchColumns(array('display_name', 'email'));
    $this->setSortColumns('display_name');
  }
  
}