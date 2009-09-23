<?php
/**
 * TableConfiguration for UllVentoryItemType
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class UllVentoryItemTypeTableConfiguration extends ullTableConfiguration
{
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
    //$nameColumnI18n = ullCoreTools::makeI18nColumnName('name');
    
    $this->setName(__('Item types', null, 'ullVentoryMessages'));
    $this->setSearchColumns(array('slug'));
    $this->setSortColumns('slug');
  }
  
}