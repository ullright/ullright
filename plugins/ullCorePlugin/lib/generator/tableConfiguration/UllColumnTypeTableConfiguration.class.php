<?php
/**
 * TableConfiguration for UllColumnType
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class UllColumnTypeTableConfiguration extends ullTableConfiguration
{
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
    $this->setName(__('Field types', null, 'ullCoreMessages'));
    $this->setSearchColumns(array('class', 'label'));
    $this->setOrderBy('class');
  }
  
}