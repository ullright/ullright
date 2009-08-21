<?php

class ullWidgetForeignKey extends sfWidgetFormInput
{
  
  protected function configure($options = array(), $attributes = array())
  {
    // render a input type hidden field in read mode
    $this->addOption('render_additional_hidden_field', false);
    
    $this->addRequiredOption('model');
    $this->addOption('method', '__toString');
    
    parent::configure($options, $attributes);
  }
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $return = '';
    
    if ($this->getOption('render_additional_hidden_field'))
    {
      $attributes['type'] = 'hidden';
      $return .= parent::render($name, $value, $attributes, $errors);  
    }
    
    if (empty($value))
    {
      return $return;
    }
    
    $object = Doctrine::getTable($this->getOption('model'))->find($value);
    $method = $this->getOption('method');
    $return .= $object->$method();
    
    return $return;
  }
  
}
