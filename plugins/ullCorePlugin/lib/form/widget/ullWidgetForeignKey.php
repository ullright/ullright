<?php

class ullWidgetForeignKey extends sfWidgetForm
{
  
  protected function configure($options = array(), $attributes = array())
  {
    $this->addRequiredOption('model');
    $this->addOption('method', '__toString');

    parent::configure($options, $attributes);
  }
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if (empty($value))
    {
      return '';
    }
    $object = Doctrine::getTable($this->getOption('model'))->find($value);
    
    $method = $this->getOption('method');
    return $object->$method();
  }
  
}
