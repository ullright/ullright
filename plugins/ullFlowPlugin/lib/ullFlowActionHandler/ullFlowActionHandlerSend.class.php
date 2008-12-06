<?php

class ullFlowActionHandlerSend extends ullFlowActionHandler
{
  
  function getEditWidget() 
  {
    $return = ull_submit_tag(__('Send'), array('name' => 'submit|action_slug=send'));

    return $return;
  }
  
}