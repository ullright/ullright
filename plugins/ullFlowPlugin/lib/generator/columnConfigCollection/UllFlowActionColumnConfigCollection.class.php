<?php

class UllFlowActionColumnConfigCollection extends ullColumnConfigCollection
{
  /**
   * Applies model specific custom column configuration
   *
   */
  protected function applyCustomSettings()
  {
    $this['is_status_only']->setLabel(__('Status-only action?', null, 'ullFlowMessages'));
    $this['is_enable_validation']->setLabel(__('Validation?', null, 'ullFlowMessages'));
    $this['is_notify_creator']->setLabel(__('Notify creator?', null, 'ullFlowMessages'));
    $this['is_notify_next']->setLabel(__('Notify next?', null, 'ullFlowMessages'));
    $this['is_in_resultlist']->setLabel(__('In list per default?', null, 'ullFlowMessages'));
    $this['is_show_assigned_to']->setLabel(__('Show assigned to?', null, 'ullFlowMessages'));
    $this['is_comment_mandatory']->setLabel(__('Is comment mandatory?', null, 'ullFlowMessages'));
  }
}