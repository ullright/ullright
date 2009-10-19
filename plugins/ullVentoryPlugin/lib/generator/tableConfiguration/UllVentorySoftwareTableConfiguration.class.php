<?php
/**
 * TableConfiguration for UllVentorySoftware
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class UllVentorySoftwareTableConfiguration extends ullTableConfiguration
{
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
    $this->setName(__('Software', null, 'ullVentoryMessages'));
    $this->setSearchColumns(array('name'));
    $this->setOrderBy('name');
  }
  
}