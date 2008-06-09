<?php

class ullFlowActionHandlerAssignToUser extends ullFlowActionHandler
{
  
  function getEditWidget() {

    $return = tag(
      'input' 
      , array(
        'type' => 'button'
        , 'value' => __('Assign')
        , 'onclick' => 'document.getElementById("ull_flow_action").value = "assign_to_user";this.form.submit()'
      )
    );
    
    $return .= ' ' . __('to user') . ' ';
    
    $field_name = 'ull_flow_action_handler_assign_to_user';
    $field_handler = new ullFieldHandlerUser();
    $field_handler->setPropelObject(new UllUser);
    $field_handler->setOptions($this->options);
    $field_data = $field_handler->getEditWidget('id', $field_name);
    $field_data['parameters']['options']['name'] = $field_name;
    $field_data['parameters']['options']['id'] = $field_name;
    $return .= call_user_func_array($field_data['function'], $field_data['parameters']);
    
//    ullCoreTools::printR($return);
//    exit();

    return $return;
    
     
    
  }
  
  
}

?>