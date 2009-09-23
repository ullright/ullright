<?php
/**
 * TableConfiguration for UllVentoryItemTypeAttribute
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class UllVentoryItemTypeAttributeTableConfiguration extends ullTableConfiguration
{
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
    $this->setName(__('Attributes per item type', null, 'ullVentoryMessages'));
    $this->setSearchColumns(array('id'));
    $this->setSortColumns('ull_ventory_item_type_id, ull_ventory_item_attribute_id');
  }
  
}