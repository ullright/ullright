<?php

class ullMetaWidgetString extends ullMetaWidget
{
  public function __construct($options = array())
  {
    
    if ($options['access'] == 'w')
    {
      $attributes = array();
      $validatorParams = array();
      
      if (isset($options['size']))
      {
        $attributes['maxlength']        = $options['size'];
        $validatorParams['max_length']  = $options['size'];
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