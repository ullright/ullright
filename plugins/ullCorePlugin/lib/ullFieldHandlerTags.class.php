<?php

class ullFieldHandlerTags extends ullFieldHandler 
{

  public function getShowWidget($field) {    
    
    $method_name = $this->buildPropelMethodName($field);
    
    return array ('value' => $this->propelObject->$method_name());
    
  } 

  
  public function getEditWidget($value_field, $field_name = '') {

    if (!$field_name) {
      $field_name = $value_field;
    }
    
    $method_name = $this->buildPropelMethodName($value_field);

    $return = array(
      'function'   => array ('ullFieldHandlerTags', 'ull_tags')
      , 'parameters' => array (
                          'object'    => $this->propelObject
                          ,'method'   => $method_name
                          , 'options' => array()
                          , sfContext::getInstance()->getRequest()->getParameter($field_name)
                        )
      );

//    ullCoreTools::printR($this->propelObject);
    return $return;
//    exit();
   
    
  } 
  
  public static function ull_tags($object, $method, $options = array(), $default_value = null) {

    $return = '';
    
    $options['size'] = '80';

    $return .= object_input_tag($object, $method, $options, $default_value);
    
    $tags_pop = TagPeer::getPopulars();
    sfLoader::loadHelpers(array('Tags'));
    $return .= '<br />' . __('Popular tags') . ':';
    $return .= tag_cloud($tags_pop, 'addTag("%s", "' . $options['id'] . '")', array('link_function' => 'link_to_function'));
    $return .= ull_js_add_tag();
      
    return $return;
    
  }
  

  public function updateHandler($value_field, $value, $object) {
    
    $object->setTags(strtolower($value));

    return $value;

  }  
  

}

?>