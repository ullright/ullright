<?php

class ullFieldHandlerUser extends ullFieldHandler 
{

  public function getShowWidget($field) {    
    
    $method_name = $this->buildPropelMethodName($field);
        
    $user_id = $this->propelObject->$method_name();
    
    $user = UllUserPeer::retrieveByPk($user_id);
    
    if ($user) {
//      $value = $user->__toString();
      $value = $user->getShortName();
    }

    return array('value' => @$value);
    
  } 

  
  public function getEditWidget($value_field, $field_name = '') {

//    if (!$field_name) {
//      $field_name = $value_field;
//    }
//    
    $method_name = $this->buildPropelMethodName($value_field);

    $return = array(
      'function'   => array ('ullFieldHandlerUser', 'ull_object_user_select_tag')
      , 'parameters' => array (
                          'object'    => $this->propelObject
                          , 'method'  => $method_name
                          , 'options' => $this->options
                          , sfContext::getInstance()->getRequest()->getParameter($field_name)                          
                        )
      );
      
    return $return;
    
//    ullCoreTools::printR($return);
//    exit();
   
    
  } 

  public static function ull_object_user_select_tag($object, $method, $options = array(), $default_value = null) {
    
//    ullCoreTools::printR($options);
    
    sfLoader::loadHelpers(array('Helper', 'Tag', 'Object'));
    
    if (!$selected_id = $default_value) {
      $selected_id = $object->$method();
    }
    
    $c = new Criteria();
    $c->addAscendingOrderByColumn(UllUserPeer::LAST_NAME);
    $c->addAscendingOrderByColumn(UllUserPeer::FIRST_NAME);
    
    // options:
    if (@$options['group']) {
      $group_id = UllGroupPeer::getIdByCaption($options['group']);
      $c->addJoin(UllUserPeer::ID, UllUserGroupPeer::ULL_USER_ID);
      $c->add(UllUserGroupPeer::ULL_GROUP_ID, $group_id);
    }
    $children = UllUserPeer::doSelect($c);

    $select_children = objects_for_select(
      $children
      , 'getId'
      , 'getNameLastFirst'
      , $selected_id
      , $options
    );
    
    
    
    $return = javascript_tag("
function filtery(pattern, list){
    pattern = new RegExp('^'+pattern,\"i\");
    i = 0;
    sel = 0;
    while(i < list.options.length) {
      if (pattern.test(list.options[i].text)) {
            sel = i;
            break
        }
        i++;
    }
    list.options.selectedIndex = sel;
}
");

    // handle id
    $id = _convert_method_to_name($method, $options);
    
    // override with id set in options
    if (@$options['id']) {
      $id = $options['id'];
    }

    $filter_id = $id . '_filter';
    
    $return .= input_tag($filter_id, null, array(
      'size' => '2'
      , 'id' => $filter_id
      , 'onkeyup' => 'filtery(this.value, document.getElementById("' . $id . '"))'
//      , 'title' => __('Filter by beginning to type the lastname here')
    ));
    
    $return .= ' ';
    
    $return .= select_tag(_convert_method_to_name($method, $options), $select_children, $options);    

//    ullCoreTools::printR($return);
//    exit();
    
    return $return;
    

  }
  
}

?>