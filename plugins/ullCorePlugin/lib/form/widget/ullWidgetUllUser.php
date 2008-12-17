<?php

class ullWidgetUllUser extends sfWidgetForm
{

  protected function configure($options = array(), $attributes = array())
  {
    $this->addOption('method', '__toString');

    parent::configure($options, $attributes);
  }

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if (empty($value))
    {
      return '';
    }

    return (string) Doctrine::getTable('UllUser')->findOneById($value);
  }

}

?>