<?php

class ullFlowActionHandlerAssignToGroup extends ullFlowActionHandler
{
  
  function getEditWidget() {

//    $return = tag('input', array_merge(array('type' => 'button', 'name' => 'action', 'value' => 'assign_to_user'), _convert_options_to_javascript(_convert_options($options))));
        $return = tag(
      'input' 
      , array(
        'type' => 'button'
        , 'value' => __('Assign')
        , 'onclick' => 'document.getElementById("ull_flow_action").value = "assign_to_group";this.form.submit()'
      )
    );
    
    $return .= ' ' . __('to group') . ' ';
    
    $field_name = 'ull_flow_action_handler_assign_to_group';
    $field_handler = new ullFieldHandlerGroup();
    $field_handler->setPropelObject(new UllGroup);
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