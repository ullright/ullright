<?php

class ullMetaWidgetForeignKey extends ullMetaWidget
{
  protected function addToForm()
  {
    $this->columnConfig['widgetOptions']['model'] = $this->columnConfig['relation']['model'];
    
    if ($this->isWriteMode())
    {
      $this->columnConfig['validatorOptions']['model'] = $this->columnConfig['relation']['model'];
      
      $this->addWidget(new sfWidgetFormDoctrineSelect($this->columnConfig['widgetOptions'], $this->columnConfig['widgetAttributes']));
      $this->addValidator(new sfValidatorDoctrineChoice($this->columnConfig['validatorOptions']));
    }
    else
    {
      $this->addWidget(new ullWidgetForeignKey($this->columnConfig['widgetOptions'], $this->columnConfig['widgetAttributes']));
      $this->addValidator(new sfValidatorPass());
    }
  }  
}

?>