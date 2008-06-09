<?php

class ullFlowActionHandlerSendToHelpdesk extends ullFlowActionHandler
{
  
  function getEditWidget() {

    $return = tag(
      'input' 
      , array(
        'type' => 'button'
        , 'value' => __('Send to IT-Helpdesk')
        , 'onclick' => 'document.getElementById("ull_flow_action").value = "send_to_helpdesk";this.form.submit()'
      )
    );

    return $return;
    
     
    
  }
  
  
  
}

?>