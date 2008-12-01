<?php

class ullWidgetCheckbox extends ullWidget
{
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $image = 'checkbox_unchecked';
    if ($value) {
      $image = 'checkbox_checked';
    }
    return $this->renderContentTag('img', null,
      array('src'   => '/'. sfConfig::get('app_theme', 'ullThemeDefault').'/images/forms/'.$image.'.png',
            'alt'   => __($image),
            'title' => __($image)));
  }

}

?>