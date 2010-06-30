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
    $this->setOrderBy('sequence');
  }
  
}