<?php

class ullWidgetPasswordWrite extends sfWidgetFormInputPassword
{

  public function __construct($options = array(), $attributes = array())
  {
    $this->addOption('render_pseudo_password', false);

    parent::__construct($options, $attributes);
    
    if ($this->getOption('render_pseudo_password') == true)
    {
      $this->setOption('always_render_empty', false);
    }    
  }
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if ($this->getOption('render_pseudo_password') == true && $value)
    {
      $value = '********';
    }
    
    return parent::render($name, $value, $attributes, $errors);
  }

}
