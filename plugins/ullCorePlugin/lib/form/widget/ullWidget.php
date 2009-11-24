<?php

class ullWidget extends sfWidgetForm
{
  public function __construct($options = array(), $attributes = array())
  {
    $this->addOption('suffix');
    $this->addOption('nowrap');
    parent::__construct($options, $attributes);
  }
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if (is_array($value))
    {
      $value = $value['value'];
    }
    
    $suffix = $this->getOption('suffix');
    $value = esc_entities(($suffix) ? $value . ' ' . $suffix : $value);
    
    if ($this->getOption('nowrap'))
    {
      $value = '<span style="white-space: nowrap;">' . $value . '</span>';
    }
    return (string) $value;
  }
  
  public function updateObject(Doctrine_Record $object, $values, $fieldName)
  {
    return $values;
  }
}
