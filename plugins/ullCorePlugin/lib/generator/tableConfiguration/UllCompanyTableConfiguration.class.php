<?php
/**
 * TableConfiguration for UllCompany
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class UllCompanyTableConfiguration extends ullTableConfiguration
{
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
    $this->setName(__('Companies', null, 'ullCoreMessages'));
    $this->setSearchColumns(array('name', 'short_name'));
    $this->setOrderBy('name');
  }
  
}