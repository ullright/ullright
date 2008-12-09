<?php

class ullWidgetUpload extends sfWidgetForm
{
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    
    return self::renderUploadTag($name, $value, $attributes);
  }

  public static function renderUploadTag($name, $value, $attributes = array()) 
  {
    $html = '';
//    $html .= textarea_tag($name, $value, $attributes);
    $html = input_hidden_tag($name, $value, $attributes);
    
    if ($value) 
    {
      $html .= $this->renderUploadList($value);
    }

    // extract the column name between the square brackets (fields['foobar'] => foobar)
    preg_match('/[^\[]+[\[]([^\]]+)[\]]/', $name, $matches);
    $submitName = 'submit|full_page_widget=upload|full_page_widget_column=' . $matches[1];
    
    $html .= submit_tag(__('Manage files', null, 'common'), array('name' => $submitName));
    
    return $html;
  }  
  
  public static function renderUploadList($value) 
  {
    if ($value) 
    {
      $html = '';
      $html .= '<table class="ull_flow_upload">';
      $html .= '<tr>';
      $html .= '<th>' . __('Filename', null, 'common') . '</th>';
      $html .= '<th>' . __('Uploaded by', null, 'common') . '</th>';
      $html .= '<th>' . __('Uploaded at', null, 'common') . '</th>';
      $html .= '</tr>';
      
      $rows = explode("\n", $value);
        
      foreach ($rows as $row) 
      {
        $cols = explode(";", $row);
  
        if ($filename = $cols[0]) 
        {
          $path         = $cols[1];
          $mimetype     = $cols[2];
          $ull_user_id  = $cols[3];
          $user_name    = (string) Doctrine::getTable('UllUser')->find($ull_user_id);
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