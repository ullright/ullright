<?php

/**
 * Read only percentage widget
 * 
 * @author klemens.ullmann@ull.at
 *
 */
class ullWidgetPercentageRead extends ullWidget
{
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if ($value)
    {
      $cssClass = 'widget_very_low';
      $pre = '';
      $post = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
      
      if ($value >=  20)
      {
        $cssClass = 'widget_low';
        $pre = '&nbsp;&nbsp;';
        $post = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
      }
      
      if ($value >=  40)
      {
        $cssClass = 'widget_medium';
        $pre = '&nbsp;&nbsp;&nbsp;&nbsp;';
        $post = '&nbsp;&nbsp;&nbsp;&nbsp;';
      }
      
      if ($value >=  60)
      {
        $cssClass = 'widget_high';
        $pre = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        $post = '&nbsp;&nbsp;';
      }
      
      if ($value >=  80)
      {
        $cssClass = 'widget_very_high';
        $pre = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        $post = '';
      }
      
      return '<span class="' . $cssClass . '">' . $pre . $value . '%' . $post . '</span>';
    }
  } 
}