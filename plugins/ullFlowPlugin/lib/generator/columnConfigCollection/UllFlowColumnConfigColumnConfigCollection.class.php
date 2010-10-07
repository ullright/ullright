<?php

class UllFlowColumnConfigColumnConfigCollection extends ullColumnConfigCollection
{
  /**
   * Applies model specific custom column configuration
   *
   */
  protected function applyCustomSettings()
  {
    $this['ull_flow_app_id']->setLabel(__('Application', null, 'ullFlowMessages'));
    $this['is_subject']->setLabel(__('Is subject column?', null, 'ullFlowMessages'));
    $this['is_priority']->setLabel(__('Is priority column?', null, 'ullFlowMessages'));
    $this['is_tagging']->setLabel(__('Is tagging column?', null, 'ullFlowMessages'));
    $this['is_project']->setLabel(__('Is project column?', null, 'ullFlowMessages'));
    $this['is_due_date']->setLabel(__('Is due date column?', null, 'ullFlowMessages'));
  }
}