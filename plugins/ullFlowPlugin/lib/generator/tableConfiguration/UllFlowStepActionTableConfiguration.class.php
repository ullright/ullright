<?php
/**
 * TableConfiguration for UllFlowStepAction
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class UllFlowStepActionTableConfiguration extends ullTableConfiguration
{
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
    $this->setName(__('Actions for Steps', null, 'ullFlowMessages'));
    $this->setSearchColumns(array('sequence', 'UllFlowStep->label', 'UllFlowStep->UllFlowApp->label'));
    $this->setOrderBy('UllFlowStep->UllFlowApp->label, sequence');
    $this->setListColumns(array(
      'UllFlowStep->UllFlowApp->label',
      'UllFlowStep->label',
      'ull_flow_action_id',
      'options',
      'sequence',
    ));
    $this->setCustomRelationName('UllFlowStep->UllFlowApp', __('Workflow', null, 'ullFlowMessages'));
    $this->setCustomRelationName('UllFlowStep', __('Step', null, 'ullFlowMessages'));
  }
  
}