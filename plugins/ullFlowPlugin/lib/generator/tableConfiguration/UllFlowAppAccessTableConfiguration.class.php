<?php
/**
 * TableConfiguration
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class UllFlowAppAccessTableConfiguration extends ullTableConfiguration
{
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
    $this->setName(__('Workflow access rights', null, 'ullFlowMessages'));
    $this->setSearchColumns(array('ull_flow_app_id'));
    $this->setOrderBy('ull_flow_app_id');
    $this
      ->setPlugin('ullFlowPlugin')
      ->setBreadcrumbClass('ullFlowBreadcrumbTree')
    ;           
  }
  
}