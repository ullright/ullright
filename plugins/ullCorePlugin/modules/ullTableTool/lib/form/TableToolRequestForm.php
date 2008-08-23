<?php

class TableToolRequestForm extends sfForm
{
  
  public function configure()
  {
    $this->setWidgets(array(
        'id'          => new sfWidgetFormInputHidden,
        'table_name'  => new sfWidgetFormInputHidden,
        
    ));
    
    $this->widgetSchema->setNameFormat('table_tool[%s]');
  
    $this->setValidators(array(
        'id'          => new sfValidatorInteger(),
        'table_name'  => new sfValidatorString(),
    ));
  }
  
}
