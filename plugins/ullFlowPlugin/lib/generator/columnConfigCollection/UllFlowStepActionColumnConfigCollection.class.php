<?php

class UllFlowStepActionColumnConfigCollection extends ullColumnConfigCollection
{
  /**
   * Applies model specific custom column configuration
   *
   */
  protected function applyCustomSettings()
  {
    $this['ull_flow_step_id']->setLabel(__('Step', null, 'ullFlowMessages'));
    $this['ull_flow_action_id']->setLabel(__('Action', null, 'ullFlowMessages'));
  }
}