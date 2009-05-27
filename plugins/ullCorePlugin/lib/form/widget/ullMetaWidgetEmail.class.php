<?php

class ullMetaWidgetEmail extends ullMetaWidget
{
  protected function configureWriteMode($withValidator = true)
  {
    if (!isset($this->columnConfig['widgetAttributes']['size']))
    {
      $this->columnConfig['widgetAttributes']['size'] = '30';
    }
     
    $this->addWidget(new sfWidgetFormInput($this->columnConfig['widgetOptions'], $this->columnConfig['widgetAttributes']));
    $this->addValidator(
      ($withValidator == true) ?
        new sfValidatorEmail($this->columnConfig['validatorOptions']) :
        new sfValidatorString($this->columnConfig['validatorOptions']));
  }

  protected function configureSearchMode()
  {
    
    $this->configureWriteMode(false);
  }

  protected function configureReadMode()
  {
    unset($this->columnConfig['widgetAttributes']['maxlength']);
    $this->addWidget(new ullWidgetEmail($this->columnConfig['widgetOptions'], $this->columnConfig['widgetAttributes']));
    $this->addValidator(new sfValidatorPass());
  }
}