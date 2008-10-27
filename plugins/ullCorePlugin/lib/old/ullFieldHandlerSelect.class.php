<?php

/*
 * creates a select box using data from ull_select & ull_select_chilg
 */

class ullFieldHandlerSelect extends ullFieldHandler 
{

  public function getShowWidget($field) {

//    ullCoreTools::printR($field);
//    ullCoreTools::printR($this->propelObject);
    
    // == get value
    $method_name = $this->buildPropelMethodName($field);
    $value = $this->propelObject->$method_name();
//    ullCoreTools::printR($value);    
    
//    $ull_select_slug = $this->options['ull_select'];
//    
//    $ull_select_id = UllSelectPeer::retrieveIDBySlug($ull_select_slug);
    
    $child = UllSelectChildPeer::retrieveByPK($value);
    
    if ($child) {
      $return = $child->__toString();
      
      return array('value' => $return);
    }
      

    
  } 

  public function getEditWidget($value_field, $field_name = '', $default_value = '') {   
   
    sfLoader::loadHelpers(array('Helper', 'Tag', 'Object'));

    if (!$field_name) {
      $field_name = $value_field;
    }

    if (!$selected_id = sfContext::getInstance()->getRequest()->getParameter($field_name)) {
      $method_name = 'get' . sfInflector::classify($value_field);
      $selected_id = $this->propelObject->$method_name();
    }

    if (!$selected_id) {
    	$selected_id = $default_value;
    }
    
    $ull_select_slug = $this->options['ull_select'];

    $ull_select_id = UllSelectPeer::retrieveIDBySlug($ull_select_slug);
    
    $c = new Criteria();
    $c->add(UllSelectChildPeer::ULL_SELECT_ID, $ull_select_id);
    $c->addAscendingOrderByColumn(UllSelectChildPeer::SEQUENCE);
    
    $children = UllSelectChildPeer::doSelect($c);
    
//    ullCoreTools::printR($children);
    
//    $children_arr = array();
//    foreach ($children as $child) {
//      $children_arr[$child->getId()] = (string)$child;
//    }

    $select_children = objects_for_select(
      $children
      , 'getId'
      , '__toString'
      , $selected_id
      , $this->options
    );

    return array(
      'function'   => 'select_tag'
      , 'parameters' => array (
                          'field'    => $field_name
                          ,'select_children' => $select_children
                          , 'options' => array()
                        )
      );

  }  

}

?>