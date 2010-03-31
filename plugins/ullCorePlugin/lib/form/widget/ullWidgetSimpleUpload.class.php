<?php

class ullWidgetSimpleUpload extends ullWidgetFormInput
{
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {

    $widget = new sfWidgetFormInputFile(array(
      'label' => __('Photo', null, 'common'),
    ));

    return $widget->render($name, $value, $attributes, $errors);

  }
}


