<?php

class ullFlowActionHandlerClose extends ullFlowActionHandler
{
  
  function getEditWidget() {

//    $return = tag('input', array_merge(array('type' => 'button', 'name' => 'action', 'value' => 'assign_to_user'), _convert_options_to_javascript(_convert_options($options))));
        $return = tag(
      'input' 
      , array(
        'type' => 'button'
        , 'value' => __('Close')
        , 'onclick' => 'document.getElementById("ull_flow_action").value = "close";this.form.submit()'
      )
    );

    return $return;
    
     
    
  }
  
  
}

?>