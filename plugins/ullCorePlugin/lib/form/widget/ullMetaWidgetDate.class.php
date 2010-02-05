<?php

class ullMetaWidgetDate extends ullMetaWidget
{
  protected function configureWriteMode()
  {
    if ($this->columnConfig->getWidgetAttribute('size') == null)
    {
      $this->columnConfig->setWidgetAttribute('size', '10');
    }
     
    $this->addWidget(new ullWidgetDateWrite($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    $this->addValidator(new sfValidatorDate($this->columnConfig->getValidatorOptions()));
  }

  protected function configureReadMode()
  {
    if ($this->columnConfig->getOption('show_weekday'))
    {
      $this->columnConfig->setWidgetOption('show_weekday', true);
    }
    
    $this->addWidget(new ullWidgetDateRead($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    $this->addValidator(new sfValidatorPass());
  }

  public function getSearchType()
  {
    return 'rangeDate';
  }
}
