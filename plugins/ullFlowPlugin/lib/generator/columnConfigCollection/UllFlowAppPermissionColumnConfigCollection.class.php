<?php

class UllFlowAppPermissionColumnConfigCollection extends ullColumnConfigCollection
{
  /**
   * Applies model specific custom column configuration
   *
   */
  protected function applyCustomSettings()
  {
    $this['ull_flow_app_id']->setLabel(__('Application', null, 'ullFlowMessages'));
  }
}