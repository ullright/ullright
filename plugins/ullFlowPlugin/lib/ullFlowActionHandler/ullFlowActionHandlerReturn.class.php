<?php

class ullFlowActionHandlerReturn extends ullFlowActionHandler
{
  
  /**
   * Renders the html output of the action handler for the ullFlow edit action
   *
   * @return string 
   */
  function render() 
  {
    $return = ull_submit_tag(__('Return'), array('name' => 'submit|action_slug=return'));
    return $return;
  }
  
  public function getNext()
  {
//    var_dump($this->getHistoryOneStepBack());
    return $this->getHistoryOneStepBack();
  }  
  
}
