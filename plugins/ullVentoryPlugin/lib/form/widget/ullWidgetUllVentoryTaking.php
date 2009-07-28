<?php

class ullWidgetUllVentoryTaking extends ullWidget
{
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if ($value)
    {
      return link_to(ull_image_tag('ok'), 'ullVentory/toogleInventoryTaking');  
    }
    else
    {
      return link_to(ull_image_tag('notok'), 'ullVentory/toogleInventoryTaking');
    }
  } 
}