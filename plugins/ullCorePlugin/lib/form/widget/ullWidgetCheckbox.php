<?php

class ullWidgetCheckbox extends ullWidget
{
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $image = 'checkbox_unchecked';
    
    if ($value) 
    {
      $image = 'checkbox_checked';
    }
    
    return ull_image_tag($image, array(), 9, 9, 'ullCore');
  }

}

?>