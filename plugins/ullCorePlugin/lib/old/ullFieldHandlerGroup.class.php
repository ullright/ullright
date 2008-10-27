<?php

class ullFieldHandlerGroup extends ullFieldHandler 
{

  public function getShowWidget($field) {    
    
    $method_name = $this->buildPropelMethodName($field);
        
    $group_id = $this->propelObject->$method_name();
    
    $group = (string)UllGroupPeer::retrieveByPk($group_id);

    return array('value' => $group);
    
  } 

  public function getEditWidget($value_field, $field_name = '') {   

//    sfLoader::loadHelpers('Helper', 'Object');
//    sfLoader::loadHelpers('Form','Helper','Object');
    
//    $method_name = $this->buildPropelMethodName($field);

    if (!$field_name) {
      $field_name = $value_field;
    }
    
    return array(
      'function'   => 'object_select_tag'
      , 'parameters' => array (
                          'object'    => $this->propelObject
                          , 'method'  => 'getId'
                          , 'options' => array('related_class' => 'UllGroup')
                          , sfContext::getInstance()->getRequest()->getParameter($field_name)                          
                        )
      );
   
    
  }  
  
}

?>