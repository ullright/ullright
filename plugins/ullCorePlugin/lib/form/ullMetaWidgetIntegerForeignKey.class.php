<?php

class ullMetaWidgetForeignKey extends ullMetaWidget
{
  public function __construct($options = array())
  {
    
//    var_dump($options);
    
    if ($options['access'] == 'w')
    {
      $widgetOptions = array(
          'model' => $options['relation']['model'],
      );
//      var_dump($widgetOptions);
      $this->sfWidget = new sfWidgetFormDoctrineSelect($widgetOptions);
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