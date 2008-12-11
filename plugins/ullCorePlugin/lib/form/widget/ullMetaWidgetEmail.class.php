<?php

class ullMetaWidgetEmail extends ullMetaWidget
{
  protected function addToForm()
  {
    if ($this->isWriteMode())
    {
      $this->addWidget(new sfWidgetFormInput($this->columnConfig['widgetOptions'], $this->columnConfig['widgetAttributes']));
      $this->addValidator(new sfValidatorEmail($this->columnConfig['validatorOptions']));
    }
    else
    {
      unset($this->columnConfig['widgetAttributes']['maxlength']);
      $this->addWidget(new ullWidgetEmail($this->columnConfig['widgetOptions'], $this->columnConfig['widgetAttributes']));
      $this->addValidator(new sfValidatorPass());
    }
    
  }  
}

?>