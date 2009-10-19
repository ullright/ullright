<?php
/**
 * TableConfiguration for UllFlowStep
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class UllFlowStepTableConfiguration extends ullTableConfiguration
{
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
    $this->setName(__('Steps', null, 'ullFlowMessages'));
    $this->setSearchColumns(array('slug'));
    $this->setOrderBy('ull_flow_app_id');
  }
  
}