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
      if (!isset($this->columnConfig['widgetOptions']['min']))
      {
        $this->columnConfig['widgetOptions']['min'] = 0;
      }
      $this->columnConfig['validatorOptions']['min'] = $this->columnConfig['widgetOptions']['min'];
      
      // parse 'max'
      if (!isset($this->columnConfig['widgetOptions']['max']))
      {
        $this->columnConfig['widgetOptions']['max'] = 100;
      }
      $this->columnConfig['validatorOptions']['max'] = $this->columnConfig['widgetOptions']['max'];    
      
      // parse 'step'
      if (!isset($this->columnConfig['widgetOptions']['step']))
      {
        $this->columnConfig['widgetOptions']['step'] = 1;
      }    
      
      // parse 'orientation'
      if (!isset($this->columnConfig['widgetOptions']['orientation']))
      {
        $this->columnConfig['widgetOptions']['orientation'] = 'horizontal';
      }

//    var_dump($this->columnConfig);die;      
      $this->addWidget(new ullWidgetPercentageWrite($this->columnConfig['widgetOptions'], $this->columnConfig['widgetAttributes']));
      $this->addValidator(new sfValidatorInteger($this->columnConfig['validatorOptions']));
    }
    else
    {
      unset($this->columnConfig['widgetOptions']['min']);
      unset($this->columnConfig['widgetOptions']['max']);
      unset($this->columnConfig['widgetOptions']['step']);
      unset($this->columnConfig['widgetOptions']['orientation']);
      
      $this->addWidget(new ullWidgetPercentageRead($this->columnConfig['widgetOptions'], $this->columnConfig['widgetAttributes']));
      $this->addValidator(new sfValidatorPass());
    }
    
  }  
}
