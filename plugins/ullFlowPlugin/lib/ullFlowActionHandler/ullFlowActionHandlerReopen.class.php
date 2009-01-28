<?php

class ullFlowActionHandlerReopen extends ullFlowActionHandler
{
  
  /**
   * Renders the html output of the action handler for the ullFlow edit action
   *
   * @return string 
   */
  function render() 
  {
    $return = ull_submit_tag(__('Reopen'), array('name' => 'submit|action_slug=reopen'));
    return $return;
  }
  
}
