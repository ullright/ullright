<?php

/**
 * Renders a hex-code as a colored rectangle.
 */
class ullWidgetColorRead extends ullWidget
{
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if (empty($value))
    {
      return '';
    }
    
    //TODO: Check $value for malicious code?
    return '<div style="text-align: center;"><span class="color_widget_read" style="background-color: #' . $value . ';">'
    . $value . '</span></div>';
  }
}