<?php

class ullFlowActionHandlerDoneReview extends ullFlowActionHandler
{
  
  /**
   * Renders the html output of the action handler for the ullFlow edit action
   *
   * @return string 
   */
  function render() 
  {
    $return = ull_submit_tag(__('Done, assign to creator for review', null, 'ullFlowMessages'), array('name' => 'submit|action_slug=done_review'));
    return $return;
  }
  
  public function getNext()
  {
    return array ('entity' => $this->form->getObject()->Creator); 
  }  
  
}
