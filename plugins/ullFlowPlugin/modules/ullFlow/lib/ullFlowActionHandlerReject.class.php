<?php

class ullFlowActionHandlerReject extends ullFlowActionHandler
{
  
  public function getEditWidget() {

//    $return = tag('input', array_merge(array('type' => 'button', 'name' => 'action', 'value' => 'assign_to_user'), _convert_options_to_javascript(_convert_options($options))));
        $return = tag(
      'input' 
      , array(
        'type' => 'button'
        , 'value' => __('Reject')
        , 'onclick' => 'document.getElementById("ull_flow_action").value = "reject";this.form.submit()'
      )
    );

    return $return;
    
  }
  
  
  public function updateHandler() {
    
    $this->setParamsOneStepBackwards();

    return $this->params;
    
  }
  
  public function getCustomMailer() {
    
    return new ullFlowMailActionReject();
    
  }
  
}

?>