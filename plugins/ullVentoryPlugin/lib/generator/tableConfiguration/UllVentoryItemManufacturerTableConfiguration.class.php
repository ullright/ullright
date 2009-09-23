<?php
/**
 * TableConfiguration for UllVentoryItemManufacturer
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class UllVentoryItemManufacturerTableConfiguration extends ullTableConfiguration
{
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
    $this->setName(__('Item manufacturers', null, 'ullVentoryMessages'));
    $this->setSearchColumns(array('name'));
    $this->setSortColumns('name');
  }
  
}