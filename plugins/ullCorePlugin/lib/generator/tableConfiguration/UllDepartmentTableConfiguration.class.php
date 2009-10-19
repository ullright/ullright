<?php
/**
 * TableConfiguration for UllDepartment
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class UllDepartmentTableConfiguration extends ullTableConfiguration
{
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
    $this->setName(__('Departments', null, 'ullCoreMessages'));
    $this->setSearchColumns(array('name', 'short_name'));
    $this->setOrderBy('name');
  }
  
}