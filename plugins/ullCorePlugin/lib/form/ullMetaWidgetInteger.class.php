<?php

class ullMetaWidgetInteger extends ullMetaWidget
{
  public function __construct($options = array())
  {
    
    if ($options['access'] == 'w')
    {
      $this->sfWidget = new sfWidgetFormInput();
      $this->sfValidator = new sfValidatorInteger();
    }
    else
    {
      $this->sfWidget = new ullWidget();
      $this->sfValidator = new sfValidatorPass();
    }
    
  }  
}

?>