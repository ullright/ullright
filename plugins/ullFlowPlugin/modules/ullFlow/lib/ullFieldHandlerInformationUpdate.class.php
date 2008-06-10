<?php

class ullFieldHandlerInformationUpdate extends ullFieldHandler 
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
    
    $method_name = $this->buildPropelMethodName($value_field);
    
//    $value = $this->propelObject->$method_name();
    
    
    

//    ullCoreTools::printR($field_name);
//    ullCoreTools::printR(sfContext::getInstance()->getRequest()->getParameter($field_name));
    
    
    
    return array(
      'function' => array ('ullFieldHandlerInformationUpdate', 'ull_object_information_update')
      , 'parameters' => array(
                          'object'    => $this->propelObject
                          ,'method'   => $method_name
                          , 'options' => array('size' => '60x4')
                          , sfContext::getInstance()->getRequest()->getParameter($field_name)
                        )
    );
  }

  
  public static function ull_object_information_update($object, $method, $options = array(), $default_value = null) {
    
//    ullCoreTools::printR($options);
    
    sfLoader::loadHelpers(array('Helper', 'Tag', 'Object'));
    
    $value = $object->$method();
    
    $return = '';
    
    if ($value) {
      $return .= '<div class="ull_flow_fieldtype_information_update">' . nl2br($value) . '</div>';
    }
    
    
//    if (!$selected_id = $default_value) {
//      $selected_id = $object->$method();
//    }

    $options['size'] = '92x2'; 
    
    $return .= textarea_tag(_convert_method_to_name($method, $options), $default_value, $options);
    
    
    return $return;
    

  }
  
  public function updateHandler($value_field, $add_value, $object) {

    $method_name  = $this->buildPropelMethodName($value_field);
    $value    = $this->propelObject->$method_name();
  
    $user_id      = sfContext::getInstance()->getUser()->getAttribute('user_id');
    $user         = UllUserPeer::retrieveByPK($user_id);
    $user_name    = $user->__toString();
    $now          = ull_format_datetime(date('Y-m-d H:i:s'));

    $return_value = $value;
    
    if ($add_value) {

      $return_value = "$user_name ($now):\n$add_value\n";

      // new line handling improvement
      if (substr($add_value, -1) <> "\n") {
        $return_value .= "\n";
      }
      
      $return_value .= $value;
      
    }

    return $return_value;

  }
}

?>