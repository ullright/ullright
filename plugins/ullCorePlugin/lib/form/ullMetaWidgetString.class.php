<?php

class ullMetaWidgetString extends ullMetaWidget
{
  public function __construct($parameter = array())
  {
    
    if ($parameter['access'] == 'w')
    {
      $attributes = array();
      $validatorParams = array();
      
      if (isset($parameter['size']))
      {
        $attributes['maxlength']        = $parameter['size'];
        $validatorParams['max_length']  = $parameter['size'];
      }
      $this->sfWidget = new sfWidgetFormInput(array(), $attributes);
      
      $this->sfValidator = new sfValidatorString($validatorParams);
    }
    else
    {
      $this->sfWidget = new ullWidget();
      $this->sfValidator = new sfValidatorPass();
    }
    
  }  
}

?>