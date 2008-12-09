<?php

class ullFieldHandlerUpload extends ullFieldHandler 
{

  public function getShowWidget($field) {

//    ullCoreTools::printR($field);
//    ullCoreTools::printR($this->propelObject);
    
    sfLoader::loadHelpers(array('Url'));
    
    // == get value
    $method_name = $this->buildPropelMethodName($field);
    $value = $this->propelObject->$method_name();
    
    $return = self::ull_upload_list($value);
//    ullCoreTools::printR($value);    
    
//    $ull_select_slug = $this->options['ull_select'];
//    
//    $ull_select_id = UllSelectPeer::retrieveIDBySlug($ull_select_slug);
    

      
    return array('value' => $return);
      

    
  } 

  public function getEditWidget($value_field, $field_name = '') { 
    
    if (!$field_name) {
      $field_name = $value_field;
    }
    
    if (!$value = sfContext::getInstance()->getRequest()->getParameter($field_name)) {
      $method_name = 'get' . sfInflector::classify($value_field);
      $value = $this->propelObject->$method_name();
    }
    
    
    return array(
      'function'   => array ('ullFieldHandlerUpload', 'ull_upload_tag')
      , 'parameters' => array (
                          'name'      => $field_name
                          ,'value'    => $value
                          , 'options' => array()
                        )
      );
   
    
  }  
  
  

  
  

  
}  
  
  
  
}

?>