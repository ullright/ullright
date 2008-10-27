<?php

class ullFieldHandlerCheckbox extends ullFieldHandler 
{

  public function getShowWidget($field) {    
    
    $method_name = $this->buildPropelMethodName($field);
        
    $value = $this->propelObject->$method_name();
    
    if ($value) {
      $image = 'checkbox_checked';
    } else {
      $image = 'checkbox_unchecked';
    }
    
//    echo "halllllllo";
    
//    return image_tag(
//      '/' 
//        . sfConfig::get('app_theme', 'ullThemeDefault')
//        . '/images/forms/' . $image . '.png',
//      'alt=' . __($image) . ' title=' . __($image)
//    );
            
    return array(
      'function'   => 'image_tag'
      , 'parameters' => array (
                          '/'
                            . sfConfig::get('app_theme', 'ullThemeDefault')
                            . '/images/forms/' . $image . '.png'
                          ,'alt=' . __($image) . ' title=' . __($image)
                        )
    );            
            
    
  } 

  public function getEditWidget($value_field, $field_name = '') {

    if (!$field_name) {
      $field_name = $value_field;
    }
    
    $method_name = $this->buildPropelMethodName($value_field);
    
    return array(
      'function'   => 'ull_object_checkbox_tag'
      , 'parameters' => array (
                          'object'    => $this->propelObject
                          , 'method'  => $method_name
                          , 'options' => array()
                          , sfContext::getInstance()->getRequest()->getParameterHolder()->get($field_name)
                        )
      );
   
          
    
  }  
  
}

?>