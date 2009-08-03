<?php

class ullWidgetUllVentoryTaking extends ullWidget
{
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if ($value)
    {
      $title = __('Audited during latest inventory taking', null, 'ullVentoryMessages');
      $options = array('alt' => $title, 'title' => $title);
      return link_to(ull_image_tag('ok', $options), $attributes['href']);  
    }
    else
    {
      $title = __('Not yet audited during latest inventory taking', null, 'ullVentoryMessages');
      $options = array('alt' => $title, 'title' => $title);
      return link_to(ull_image_tag('notok', $options), $attributes['href']);
    }
  } 
}