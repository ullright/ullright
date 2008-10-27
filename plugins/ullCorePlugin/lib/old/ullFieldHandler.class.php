<?php

class ullFieldHandler 
{
  
  protected 
    $propelObject     = null
    , $options        = array()
  ;
    

  public function setPropelObject($propelObject) {
    $this->propelObject = $propelObject;
  }
  
  public function setOptions($options) {
    
    // convert string options to array
    if (!is_array($options)) {
      $options = sfToolkit::stringToArray($options);
    }
    
    $this->options = $options;
  }

  
  public function getShowWidget($value_field) {    
    
    $method_name = $this->buildPropelMethodName($value_field);
        
//    return object_input_tag($this->propelObject, $method_name);
    return array ('value' => $this->propelObject->$method_name());
    
  }

  
  public function getEditWidget($value_field, $field_name = '') {

//    ullCoreTools::printR($field_name);
//    ullCoreTools::printR(sfContext::getInstance()->getRequest()->getParameter($field_name));
    
    
    if (!$field_name) {
      $field_name = $value_field;
    }

    
    $method_name = $this->buildPropelMethodName($value_field);
    
    //default size
    if(!isset($this->options['size'])) {
      $this->options['size'] = '80';
    }
    
    $return  = array(
      'function' => 'object_input_tag'
      , 'parameters' => array(
                          'object' => $this->propelObject
                          ,'method' => $method_name
                          ,'options' => $this->options
                          
                        )
      );

    // fillin for form errors:      
    if (sfContext::getInstance()->getRequest()->hasParameter($field_name)) {
      $return['parameters']['options']['value'] = 
        sfContext::getInstance()->getRequest()->getParameter($field_name);
    }      
      
    return $return;
    
  }  

  
  protected function buildPropelMethodName($field) {
    
    return 'get' . sfInflector::camelize(strtolower($field));
    
  }
  
}

?>