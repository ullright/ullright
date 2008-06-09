<?php

class ullFlowActionHandlerSend extends ullFlowActionHandler
{
  
  function getEditWidget() {

//    $return = tag('input', array_merge(array('type' => 'button', 'name' => 'action', 'value' => 'assign_to_user'), _convert_options_to_javascript(_convert_options($options))));
        $return = tag(
      'input' 
      , array(
        'type' => 'button'
        , 'value' => __('Send')
        , 'onclick' => 'document.getElementById("ull_flow_action").value = "send";this.form.submit()'
      )
    );

    return $return;
    
     
    
  }
  
  
}

?>