<?php
/**
 * TableConfiguration for UllVentoryTaking
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class UllVentoryTakingTableConfiguration extends ullTableConfiguration
{
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
    $this->setName(__('Inventory takings', null, 'ullVentoryMessages'));
    $this->setSearchColumns(array('name'));
    $this->setOrderBy('name');
    $this
      ->setPlugin('ullVentoryPlugin')
      ->setBreadcrumbClass('ullVentoryBreadcrumbTree')
    ;    
  }
  
}