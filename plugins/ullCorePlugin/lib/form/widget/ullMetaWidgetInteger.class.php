<?php

class ullMetaWidgetInteger extends ullMetaWidget
{
  protected function addToForm()
  {
    
//    var_dump($columnConfig);
    
    if ($this->isWriteMode())
    {
      $this->addWidget(new sfWidgetFormInput($this->columnConfig['widgetOptions'], $this->columnConfig['widgetAttributes']));
      $this->addValidator(new sfValidatorInteger($this->columnConfig['validatorOptions']));
    }
    else
    {
      $this->addWidget(new ullWidget($this->columnConfig['widgetOptions'], $this->columnConfig['widgetAttributes']));
      $this->addValidator(new sfValidatorPass());
    }
    
  }  
}

?>