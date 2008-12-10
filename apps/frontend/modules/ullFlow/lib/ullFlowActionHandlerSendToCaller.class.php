<?php

class ullFlowActionHandlerSendToCaller extends ullFlowActionHandler
{
  
  function getEditWidget() {

    $return = tag(
      'input' 
      , array(
        'type' => 'button'
        , 'value' => __('Send to caller')
        , 'onclick' => 'document.getElementById("ull_flow_action").value = "send_to_caller";this.form.submit()'
      )
    );

    return $return;
    
  }
  
  
  
}

?>