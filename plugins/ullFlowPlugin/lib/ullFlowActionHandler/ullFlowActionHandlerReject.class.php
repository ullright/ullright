<?php

class ullFlowActionHandlerReject extends ullFlowActionHandler
{
  
  /**
   * Renders the html output of the action handler for the ullFlow edit action
   *
   * @return string 
   */
  function render() 
  {
    $return = ull_submit_tag(__('Reject'), array('name' => 'submit|action_slug=reject'));
    return $return;
  }
  
  public function getNext()
  {
    return $this->getNextFromPreviousStep();
  }  
  
  public function sendMail() 
  {
    $mail = new ullFlowMailNotifyCreator($this->getForm()->getObject());
    $mail->send();
  }
  
}
