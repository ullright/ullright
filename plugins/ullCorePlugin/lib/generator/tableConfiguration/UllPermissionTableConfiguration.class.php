<?php
/**
 * TableConfiguration for UllPermission
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class UllPermissionTableConfiguration extends ullTableConfiguration
{
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
    $this->setName(__('Permissions', null, 'ullCoreMessages'));
    $this->setSearchColumns(array('slug'));
    $this->setSortColumns('slug');
  }
  
}