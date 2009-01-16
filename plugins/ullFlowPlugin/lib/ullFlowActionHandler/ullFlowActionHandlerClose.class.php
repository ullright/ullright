<?php

class ullFlowActionHandlerClose extends ullFlowActionHandler
{
  /**
   * Renders the html output of the action handler for the ullFlow edit action
   *
   * @return string 
   */
  function render() 
  {
    $return = ull_submit_tag(__('Close'), array('name' => 'submit|action_slug=close'));
    return $return;
  }
  
}