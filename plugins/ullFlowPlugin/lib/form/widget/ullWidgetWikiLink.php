<?php

class ullWidgetWikiLink extends sfWidgetForm
{
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    
    return self::renderWikiLinkTag($name, $value, $attributes);
  }

  public static function renderWikiLinkTag($name, $value, $attributes = array()) 
  {
    $html = '';
//    $html .= textarea_tag($name, $value, $attributes);
    $html = input_hidden_tag($name, $value, $attributes);
    
    if ($value) 
    {
      $html .= self::renderWikiLinkList($value);
    }

    // extract the column name between the square brackets (fields['foobar'] => foobar)
    preg_match('/[^\[]+[\[]([^\]]+)[\]]/', $name, $matches);
    $submitName = 'submit|full_page_widget=wiki_link|full_page_widget_column=' . $matches[1];
    
    $html .= submit_tag(__('Manage wiki links', null, 'common'), array('name' => $submitName));
    
    return $html;
  }  
  
  public static function renderWikiLinkList($value, $allowDelete = false) 
  {
    if ($value) 
    {
      $html = '';
      $html .= '<table class="ull_flow_upload">';
      $html .= '<thead>';
      $html .= '<tr>';
//      $html .= '<th>' . __('Wiki DocID', null, 'common') . '</th>';
      $html .= '<th>' . __('Subject', null, 'common') . '</th>';
      if ($allowDelete) 
      {
        $html .= '<th></th>';
      }
      $html .= '</tr>';
      $html .= '</thead>';
      $html .= '<tbody>';
      
      $rows = explode("\n", $value);
        
      foreach ($rows as $row) 
      {
        $cols = explode(";", $row);
        
        if ($ullWikiId = $cols[0]) 
        {
          $ullWiki = Doctrine::getTable('UllWiki')->find($ullWikiId);
          if ($ullWiki) 
          {
            $subject = $ullWiki->subject;
          } 
          else 
          {
            $subject = 'Invalid Wiki Doc!';
          }
          
          $html .= '<tr>';
//          $html .= '<td>' . link_to($ullWikiId, 'ullWiki/show?docid=' . $ullWikiId) . '</td>';
          $html .= '<td>' . link_to($subject, 'ullWiki/show?docid=' . $ullWikiId) . '</td>';
//          if ($allowDelete) 
//          {
//            $html .= '<td>' . ull_icon_to_function('delete_line(' . $line_num . ')', 'delete', 'confirm='.__('Are you sure?', null, 'common')) . '</td>';
//          }
//          $html .= '</tr>';        
        }            
      }
      $html .= '</tbody>';
      $html .= '</table>';
  
      return $html;
    }  
  }
  
}