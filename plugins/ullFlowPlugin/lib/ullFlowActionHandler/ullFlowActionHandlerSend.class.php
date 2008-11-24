<?php

class ullFlowActionHandlerSend extends ullFlowActionHandler
{
  
  function getEditWidget() 
  {
    $return = ull_submit_tag(__('Send'), array('name' => 'submit_send'));

    return $return;
  }
  
}