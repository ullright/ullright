<?php
/**
 * TableConfiguration for UllVentoryItemAttribute
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class UllVentoryItemAttributeTableConfiguration extends ullTableConfiguration
{
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
    //$nameColumnI18n = ullCoreTools::makeI18nColumnName('name');
    
    $this->setName(__('Item attributes', null, 'ullVentoryMessages'));
    $this->setSearchColumns(array('slug'));
    $this->setOrderBy('slug');
    $this
      ->setPlugin('ullVentoryPlugin')
      ->setBreadcrumbClass('ullVentoryBreadcrumbTree')
    ;    
  }
  
}