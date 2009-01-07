<?php

class ullWidgetLink extends ullWidget
{

//  protected function configure($options = array(), $attributes = array())
//  {
//    $this->addRequiredOption('internal_uri');
//
//    parent::configure($options, $attributes);
//  }  
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    return '<b>' . $this->renderContentTag('a', $value, $attributes) . '</b>';
  }

}

?>