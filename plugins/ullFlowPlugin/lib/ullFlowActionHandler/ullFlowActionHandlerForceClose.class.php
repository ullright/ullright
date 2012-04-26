<?php

class ullFlowActionHandlerForceClose extends ullFlowActionHandler
{
  /**
   * Renders the html output of the action handler for the ullFlow edit action
   *
   * @return string 
   */
  function render() 
  {
    $return = ull_submit_tag(__('Force close'), array('name' => 'submit|action_slug=force_close'));
    return $return;
  }
  
  /**
   * Always assign the doc to the creator when closed
   */
  public function getNext()
  {
    return array();
  }  
  
}