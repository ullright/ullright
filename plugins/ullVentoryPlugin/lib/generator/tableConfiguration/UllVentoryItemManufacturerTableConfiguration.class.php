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
    $this
      ->setName(__('Item manufacturers', null, 'ullVentoryMessages'))
      ->setSearchColumns(array('name'))
      ->setOrderBy('name')
      ->setForeignRelationName(__('Manufacturer', null, 'ullVentoryMessages'))
      ->setPlugin('ullVentoryPlugin')
      ->setBreadcrumbClass('ullVentoryBreadcrumbTree')      
    ;
  }
  
}