<?php

class ullFlowActionHandlerReopen extends ullFlowActionHandler
{
  
  function getEditWidget() {

    $return = tag(
      'input' 
      , array(
        'type' => 'button'
        , 'value' => __('Reopen')
        , 'onclick' => 'document.getElementById("ull_flow_action").value = "reopen";this.form.submit()'
      )
    );

    return $return;
    
     
    
  }
}

?>