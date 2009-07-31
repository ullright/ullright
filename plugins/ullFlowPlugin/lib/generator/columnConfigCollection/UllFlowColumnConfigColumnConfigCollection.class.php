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
    $this['is_subject']->setLabel(__('Is subject?', null, 'ullFlowMessages'));
  }
}