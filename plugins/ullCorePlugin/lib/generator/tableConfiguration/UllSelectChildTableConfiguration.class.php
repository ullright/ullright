<?php
/**
 * TableConfiguration for UllSelectChild
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class UllSelectChildTableConfiguration extends ullTableConfiguration
{
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
    $this->setName(__('Select box entries', null, 'ullCoreMessages'));
    $this->setSearchColumns(array('ull_select_id'));
    $this->setSortColumns('ull_select_id', 'sequence');
  }
  
}