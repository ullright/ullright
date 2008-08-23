<?php

class ullMetaWidgetDateTime extends ullMetaWidget
{
  public function __construct($parameter = array())
  {
    
    if ($parameter['access'] == 'w')
    {
      $this->sfWidget = new sfWidgetFormDateTime();
      $this->sfValidator = new sfValidatorDateTime();
    }
    else
    {
      $this->sfWidget = new ullWidge();
      $this->sfValidator = new sfValidatorPass();
    }
    
  }  
}

?>