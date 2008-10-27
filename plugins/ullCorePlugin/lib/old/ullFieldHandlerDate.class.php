<?php

class ullFieldHandlerDate extends ullFieldHandler 
{

  public function getShowWidget($field) {    
    
    $method_name = $this->buildPropelMethodName($field);
    
    $value = ull_format_date($this->propelObject->$method_name());
        
    return array ('value' => $value);
    
    
    
  } 

  public function getEditWidget($value_field, $field_name = '') {   

//    sfLoader::loadHelpers('Helper', 'Object');
//    sfLoader::loadHelpers('Form','Helper','Object');
    
    if (!$field_name) {
      $field_name = $value_field;
    }    
    
    $method_name = $this->buildPropelMethodName($value_field);
    
//    echo "ccc $field";

    // parse value, use request value if available (form validation fillin)
    if ($request_value = sfContext::getInstance()->getRequest()->getParameter($field_name)) {
      $value = $request_value;
    } else {
      $value = $this->propelObject->$method_name();
    }
    
    return array(
      'function'      => 'input_date_tag'
      , 'parameters'  => array (
                            'name'      => $field_name
                            , 'value'   => $value
//                            , 'options' => array('rich' => 'true')
                            , 'options' => array()
                          )
      );
   
    
  }  
  
}

?>