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
    $this->setSearchColumns(array('ull_group_id', 'ull_permission_id'));
    $this->setOrderBy('ull_group_id');
  }
  
}