<?php

/**
 * Percentage Widget featuring a js slider
 * 
 * @author klemens.ullmann@ull.at
 */
class ullMetaWidgetPercentage extends ullMetaWidget
{
  protected function addToForm()
  {
    if ($this->isWriteMode())
    {
      // parse 'min'
      if ($this->columnConfig->getWidgetOption('min') == null)
      {
        $this->columnConfig->setWidgetOption('min', 0);
      }
      $this->columnConfig->setValidatorOption('min', $this->columnConfig->getWidgetOption('min'));
      
      // parse 'max'
      if ($this->columnConfig->getWidgetOption('max') == null)
      {
        $this->columnConfig->setWidgetOption('max', 100);
      }
      $this->columnConfig->setValidatorOption('max', $this->columnConfig->getWidgetOption('max'));
      
      // parse 'step'
      if ($this->columnConfig->getWidgetOption('step') == null)
      {
        $this->columnConfig->setWidgetOption('step', 1);
      }    
      
      // parse 'orientation'
      if ($this->columnConfig->getWidgetOption('orientation') == null)
      {
        $this->columnConfig->setWidgetOption('orientation', 'horizontal');
      }

//    var_dump($this->columnConfig);die;      
      $this->addWidget(new ullWidgetPercentageWrite($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
      $this->addValidator(new sfValidatorInteger($this->columnConfig->getValidatorOptions()));
    }
    else
    {
      $this->columnConfig->removeWidgetOption('min');
      $this->columnConfig->removeWidgetOption('max');
      $this->columnConfig->removeWidgetOption('step');
      $this->columnConfig->removeWidgetOption('orientation');
      
      $this->addWidget(new ullWidgetPercentageRead($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
      $this->addValidator(new sfValidatorPass());
    }
    
  }  
}
