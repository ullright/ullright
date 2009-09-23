<?php
/**
 * TableConfiguration for UllEmployment
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class UllEmploymentTypeTableConfiguration extends ullTableConfiguration
{
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
    //$nameColumnI18n = ullCoreTools::makeI18nColumnName('name');
    
    $this->setName(__('Employment types', null, 'ullCoreMessages'));
    $this->setSearchColumns(array('id'));
    $this->setSortColumns('id');
  }
  
}