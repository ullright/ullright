<?php

class ullMetaWidgetPriority extends ullMetaWidget
{
  protected function addToForm()
  {
    if ($this->isWriteMode())
    {
      $this->addWidget(new sfWidgetFormSelect(array('choices' => array(
        1 => __('Very high', null, 'common'),
        2 => __('High', null, 'common'), 
        3 => __('Normal', null, 'common'), 
        4 => __('Low', null, 'common'), 
        5 => __('Very low', null, 'common')
      ))));
      
      $this->addValidator(new sfValidatorChoice(
              array_merge($this->columnConfig['validatorOptions'], array('choices' => array(1, 2, 3, 4, 5)))));
    }
    else
    {
      $this->addWidget(new ullWidgetPriorityRead($this->columnConfig['widgetOptions'], $this->columnConfig['widgetAttributes']));
      $this->addValidator(new sfValidatorPass());
    }
  }
}

?>