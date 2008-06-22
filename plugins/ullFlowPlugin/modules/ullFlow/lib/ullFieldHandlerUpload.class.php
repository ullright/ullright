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
  
  
  public static function ull_upload_tag($name, $value, $options = array()) {
    
  //  $html = textarea_tag($name, $value, $options);
    $html = input_hidden_tag($name, $value, $options);
    
    if ($value) {
      $html .= self::ull_upload_list($value);
    }
  
    $html .= button_to_function(
      __('Manage files', null, 'common')
      , 'ull_field_handler_upload_popup_' . $name . '("' . $name . '")');
    
    $html .= javascript_tag('
      function ull_field_handler_upload_popup_' . $name . '(ull_flow_field) {
        document.getElementById("ull_flow_action").value  = "save_only";
        document.getElementById("external").value         = "upload";
        document.getElementById("external_field").value   = ull_flow_field;
        document.getElementById("ull_flow_form").submit();    
      }
    ');
    
    return $html;
  }
  
  
  public static function ull_upload_list($value) {
    
    
    if ($value) {
      $html = '<table class="ull_flow_upload">';
        $html .= '<tr>';
          $html .= '<th>' . __('Filename', null, 'common') . '</th>';
          $html .= '<th>' . __('Uploaded by', null, 'common') . '</th>';
          $html .= '<th>' . __('Uploaded at', null, 'common') . '</th>';
        $html .= '</tr>';
      
        $rows = explode("\n", $value);
        
        foreach ($rows as $row) {
  
          $cols = explode(";", $row);
  
          if ($filename = $cols[0]) {
            $path         = $cols[1];
            $mimetype     = $cols[2];
            $ull_user_id  = $cols[3];
            $user_name    = UllUserPeer::retrieveByPK($ull_user_id)->__toString();
            $date         = ull_format_datetime($cols[4]);

            $html .= '<tr>';
              $html .= '<td>' . ull_link_to($filename, 'http://' . $_SERVER['SERVER_NAME'] . $path, 'link_new_window=true') . '</td>';
              $html .= '<td>' . $user_name . '</td>';
              $html .= '<td>' . $date . '</td>';
            $html .= '</tr>';
          }            
        }
      $html .= '</table>';
  
      return $html;
    }
  
}  
  
  
  
}

?>