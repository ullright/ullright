<?php

class ullWidgetUllFlowApp extends ullWidget
{

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $app = UllFlowAppTable::findById($value);
    
    $path = $app->getIconPath();
    
    return '<span class="no_wrap">' . $this->renderTag('img', array(
        'src'     => $path,
        'alt'     => $app->label,
        'title'   => $app->label,
        'width'   => 16,
        'height'  => 16,
        'class'   => 'image_align_middle'
    )) . ' ' . $app->label . '</span>'; 
  }

}

?>