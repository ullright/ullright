<?php

class ullFlowActionHandlerReturn extends ullFlowActionHandler
{
  
  public function getEditWidget() {

//    $return = tag('input', array_merge(array('type' => 'button', 'name' => 'action', 'value' => 'assign_to_user'), _convert_options_to_javascript(_convert_options($options))));
        $return = tag(
      'input' 
      , array(
        'type' => 'button'
        , 'value' => __('Return')
        , 'onclick' => 'document.getElementById("ull_flow_action").value = "return";this.form.submit()'
      )
    );

    return $return;
    
     
    
  }
  
  
  public function updateHandler() {
    
    $this->setParamsOneStepBackwards();

    return $this->params;
    
  }  
  
}

?>