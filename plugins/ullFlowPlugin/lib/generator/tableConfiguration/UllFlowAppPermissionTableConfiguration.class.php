<?php
/**
 * TableConfiguration for UllFlowAppPermission
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class UllFlowAppPermissionTableConfiguration extends ullTableConfiguration
{
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
    $this->setName(__('Workflow access rights', null, 'ullFlowMessages'));
    $this->setSearchColumns(array('ull_flow_app_id'));
    $this->setSortColumns('ull_flow_app_id');
  }
  
}