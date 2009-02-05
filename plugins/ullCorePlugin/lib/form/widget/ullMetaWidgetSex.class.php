<?php

class ullMetaWidgetSex extends ullMetaWidget
{
  protected function addToForm()
  {
    if ($this->isWriteMode())
    {
      $this->addWidget(new sfWidgetFormSelect(array('choices' => array(
        '' => __('', null, 'common'), //not specified
        'f' => __('Female', null, 'common'),
        'm' => __('Male', null, 'common'), 
      ))));
      
      $this->addValidator(new sfValidatorChoice(
              array_merge($this->columnConfig['validatorOptions'], array('choices' => array('', 'f', 'm')))));
    }
    else
    {
      $this->addWidget(new ullWidgetSexRead($this->columnConfig['widgetOptions'], $this->columnConfig['widgetAttributes']));
      $this->addValidator(new sfValidatorPass());
    }
  }
}
