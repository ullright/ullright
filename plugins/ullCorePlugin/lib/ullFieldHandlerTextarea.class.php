<?php

class ullFieldHandlerTextarea extends ullFieldHandler 
{
   
  public function getShowWidget($field) {    
    
    $method_name = $this->buildPropelMethodName($field);
        
//    return object_input_tag($this->propelObject, $method_name);
    return array ('value' => nl2br($this->propelObject->$method_name()));
    
  }

  
  public function getEditWidget($value_field, $field_name = '') {

    if (!$field_name) {
      $field_name = $value_field;
    }

//    ullCoreTools::printR($field_name);
//    ullCoreTools::printR(sfContext::getInstance()->getRequest()->getParameter($field_name));
    
    $method_name = $this->buildPropelMethodName($value_field);
    
    //default size
    if(!isset($this->options['size'])) {
      $this->options['size'] = '92x4';
    }
    
    return array(
      'function' => 'object_textarea_tag'
      , 'parameters' => array(
                          'object'    => $this->propelObject
                          ,'method'   => $method_name
                          , 'options' => $this->options
                          , sfContext::getInstance()->getRequest()->getParameter($field_name)
                        )
      );
   
      
    
  }  

  
  
}

?>