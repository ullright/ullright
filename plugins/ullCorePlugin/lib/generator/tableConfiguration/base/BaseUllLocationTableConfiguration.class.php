<?php
/**
 * TableConfiguration for UllLocation
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class BaseUllLocationTableConfiguration extends ullTableConfiguration
{
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
    $this->setName(__('Locations', null, 'ullCoreMessages'));
    $this->setSearchColumns(array('name', 'short_name'));
    $this->setOrderBy('name');
  }
  
}