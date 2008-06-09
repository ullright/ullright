<?php

/*
 * interface from ullFlow to ullWiki 
 */

// TODO: use own styles, not the ones from upload module

class ullFieldHandlerWikiLink extends ullFieldHandler 
{

  public function getShowWidget($field) {

//    ullCoreTools::printR($field);
//    ullCoreTools::printR($this->propelObject);
    
    // == get value
    $method_name = $this->buildPropelMethodName($field);
    $value = $this->propelObject->$method_name();
    
    $return = self::ull_wiki_list($value);
    
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
      'function'   => array('ullFieldHandlerWikiLink', 'ull_wiki_tag')
      , 'parameters' => array (
                          'name'      => $field_name
                          ,'value'    => $value
                          , 'options' => array()
                        )
      );
   
    
  }  
  
  public static function ull_wiki_tag($name, $value, $options = array()) {
  
    $html = input_hidden_tag($name, $value, $options);
    
    if ($value) {
      $html .= self::ull_wiki_list($value);
    }
  
    $html .= button_to_function(
      __('Manage wiki links', null, 'common')
      , 'ull_field_handler_wiki_link_popup_' . $name . '("' . $name . '")');
    
    $html .= javascript_tag('
      function ull_field_handler_wiki_link_popup_' . $name . '(ull_flow_field) {
        document.getElementById("ull_flow_action").value  = "save_only";
        document.getElementById("external").value         = "wiki_link";
        document.getElementById("external_field").value   = ull_flow_field;
        document.getElementById("ull_flow_form").submit();    
      }
    ');
    
    return $html;
  }
  
  
  public static function ull_wiki_list($value, $allow_delete = false) {
    
    if ($value) {
      $html = '<table class="ull_flow_upload">';
        $html .= '<tr>';
          $html .= '<th>' . __('Wiki DocID', null, 'common') . '</th>';
          $html .= '<th>' . __('Subject', null, 'common') . '</th>';
          if ($allow_delete) {
            $html .= '<th></th>';
          }
        $html .= '</tr>';
      
        $rows = explode("\n", $value);
        
        foreach ($rows as $line_num => $row) {
  
          $cols = explode(";", $row);
  
          if ($ull_wiki_doc_id = $cols[0]) {
            $subject = UllWikiPeer::retrieveByDocid($ull_wiki_doc_id)->getSubject();
            
            $html .= '<tr>';
              $html .= '<td>' . link_to($ull_wiki_doc_id, 'ullWiki/show?docid=' . $ull_wiki_doc_id) . '</td>';
              $html .= '<td>' . link_to($subject, 'ullWiki/show?docid=' . $ull_wiki_doc_id) . '</td>';
              if ($allow_delete) {
                $html .= '<td>' . ull_icon_to_function('delete_line(' . $line_num . ')', 'delete', 'confirm='.__('Are you sure?', null, 'common')) . '</td>';
              }
            $html .= '</tr>';
          }            
        }
      $html .= '</table>';
  
      return $html;
    }
  
}
  
}

?>