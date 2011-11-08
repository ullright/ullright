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
    
    $this
      ->setName(__('Item types', null, 'ullVentoryMessages'))
      ->setSearchColumns(array('slug'))
      ->setOrderBy('slug')
      ->setForeignRelationName(__('Type', null, 'ullVentoryMessages'))
      ->setPlugin('ullVentoryPlugin')
      ->setBreadcrumbClass('ullVentoryBreadcrumbTree')
    ;
  }
  
}