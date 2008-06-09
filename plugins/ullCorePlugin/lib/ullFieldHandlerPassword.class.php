<?php

class ullFieldHandlerPassword extends ullFieldHandler
{
  
 public function getShowWidget($field) {    
    
    $method_name = $this->buildPropelMethodName($field);
        
//    return object_input_tag($this->propelObject, $method_name);
    if ($this->propelObject->$method_name()) {
      return array ('value' => __('Set'), null, 'common');  
    }
    
    
  }

  
  public function getEditWidget($value_field, $field_name = '') {

    if (!$field_name) {
      $field_name = $value_field;
    }

//    ullCoreTools::printR($field_name);
//    ullCoreTools::printR(sfContext::getInstance()->getRequest()->getParameter($field_name));
    
    $method_name = $this->buildPropelMethodName($value_field);
    
    return array(
      'function' => 'input_password_tag'
      , 'parameters' => array(
                          'name'    => $field_name
                          ,'value'   => ''
                          , 'options' => array('size' => '50') // TODO: maxlength
                        )
      );
   
      
    
  }  
  
  
}

?>