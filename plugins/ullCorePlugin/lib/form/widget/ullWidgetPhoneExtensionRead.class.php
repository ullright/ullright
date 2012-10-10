<?php

class ullWidgetPhoneExtensionRead extends ullWidget
{
  public function __construct($options = array(), $attributes = array())
  {
    $this->addOption('show_base_number');
    $this->addOption('click_to_dial', true);
    parent::__construct($options, $attributes);
  }

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $id = null;
    
    if (is_array($value))
    {
      $id = $value['id'];
      $value = $value['value'];
    }
    
    if ($this->getOption('show_base_number') && $id)
    {
      $user = Doctrine::getTable('UllEntity')->findOneById($id);
      $value = $user->UllLocation->phone_base_no . ' <em>' . $value . '<em>';
    }
    
    if ($this->getOption('click_to_dial') && $value)
    {
      $rawNumber = strip_tags(str_replace(' ', '', $value));
      $value = '<a href="tel:' . $rawNumber . '">' . $value . '</a>';
    }     
    
    return $value;
  }
}
