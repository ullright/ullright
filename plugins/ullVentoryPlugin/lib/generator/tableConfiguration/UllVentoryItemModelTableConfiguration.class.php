<?php
/**
 * TableConfiguration for UllVentoryItemModel
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class UllVentoryItemModelTableConfiguration extends ullTableConfiguration
{
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
    $this->setName(__('Item models', null, 'ullVentoryMessages'));
    $this->setSearchColumns(array('name'));
    $this->setSortColumns('ull_ventory_item_type_id, ull_ventory_item_manufacturer_id, name');
  }
  
}