<?php
/**
 * TableConfiguration for UllVentorySoftwareLicense
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class UllVentorySoftwareLicenseTableConfiguration extends ullTableConfiguration
{
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
    $this->setName(__('Software licenses', null, 'ullVentoryMessages'));
    $this->setSearchColumns(array('supplier', 'license_key', 'comment'));
    $this->setOrderBy('ull_ventory_software_id');
  }
  
}