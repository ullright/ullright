<?php

class ullMetaWidgetPriority extends ullMetaWidget
{
  protected function addToForm()
  {
    if ($this->isWriteMode())
    {
      $this->addWidget(new sfWidgetFormSelect(array('choices' =>
                  array(1 => 'Very high', 2 => 'High', 3 => 'Medium', 4 => 'Low', 5 => 'Very low'))));
      
      $this->addValidator(new sfValidatorChoice(array('choices' => array(1, 2, 3, 4, 5))));
    }
    else
    {
      $this->addWidget(new ullWidgetPriorityRead($this->columnConfig['widgetOptions'], $this->columnConfig['widgetAttributes']));
      $this->addValidator(new sfValidatorPass());
    }
  }
}

?>