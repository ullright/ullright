<?php

class ullWidgetCheckbox extends ullWidget
{

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if (is_array($value))
    {
      $value = $value['value'];
    }
    
    $image = 'checkbox_unchecked';

    if ($value)
    {
      $image = 'checkbox_checked';
    }

    return ull_image_tag($image, array('class' => $image), 9, 9, 'ullCore');
  }

}
