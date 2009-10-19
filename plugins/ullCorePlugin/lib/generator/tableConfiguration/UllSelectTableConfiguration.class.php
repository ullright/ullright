<?php
/**
 * TableConfiguration for UllSelect
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class UllSelectTableConfiguration extends ullTableConfiguration
{
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
    $this->setName(__('Select boxes', null, 'ullCoreMessages'));
    $this->setSearchColumns(array('id'));
    $this->setOrderBy('id');
  }
  
}