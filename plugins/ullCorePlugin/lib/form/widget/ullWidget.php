<?php

class ullWidget extends sfWidgetForm
{
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    return esc_entities($value);
  }
  
  public function updateObject(Doctrine_Record $object, $values, $fieldName)
  {
    return $values;
  }
}
