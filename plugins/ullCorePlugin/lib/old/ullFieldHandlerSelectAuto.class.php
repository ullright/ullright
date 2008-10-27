<?php

/*
 * creates a select box automatically for the current field
 *   using the field_name to guess the class
 *   and using __toString() function of the class
 */

class ullFieldHandlerSelectAuto extends ullFieldHandler 
{

  public function getShowWidget($field) {

//    ullCoreTools::printR($field);
//    ullCoreTools::printR($this->propelObject);
    
    // == get value
    $method_name = $this->buildPropelMethodName($field);
    $value = $this->propelObject->$method_name();
    
    
    // == get name for id
    $class_name = sfInflector::classify(str_replace('_id', '', $field));
    
    if (class_exists($class_name)) {
      
      $obj = call_user_func(array($class_name . 'Peer', 'retrieveByPk'), $value);
      
//      ullCoreTools::printR($obj);
      if ($obj) {
        $value = $obj->__toString();
      } 
      
      return array('value' => @$value);
      
    }
    
  } 

  public function getEditWidget($value_field, $field_name = '') {   
   

    if (!$field_name) {
      $field_name = $value_field;
    }
    
    $method_name = 'get' . sfInflector::classify($value_field);
    
    return array(
      'function'   => 'object_select_tag'
      , 'parameters' => array (
                          'object'    => $this->propelObject
                          , 'method'  => $method_name
                          , 'options' => array()
                          , sfContext::getInstance()->getRequest()->getParameter($field_name)
                        )
      );
   
    
  }  
  
}

?>