<?php
/**
 * TableConfiguration for UllGroupPermission
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class UllGroupPermissionTableConfiguration extends ullTableConfiguration
{
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
    $this->setName(__('Group permissions', null, 'ullCoreMessages'));
    $this->setSearchColumns(array('UllGroup->display_name', 'UllPermission->slug'));
    $this->setOrderBy('UllGroup->display_name, UllPermission->slug');
  }
  
}