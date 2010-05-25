<?php

class ullWidgetUllFlowActionRead extends ullWidget
{

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $action = UllFlowActionTable::findById($value);
    
    $path = $action->getIconPath();
    
    return '<span class="no_wrap">' . $this->renderTag('img', array(
        'src'     => $path,
        'alt'     => $action->label,
        'title'   => $action->label,
        'width'   => 16,
        'height'  => 16,
        'class'   => 'image_align_middle'
    )) . ' ' . $action->label . '</span>'; 
  }

}
