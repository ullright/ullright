<?php

/**
 * Meta widget for times
 * Example: Woke up at 6:50h
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class ullMetaWidgetTime extends ullMetaWidget
{
  protected
    $readWidget = 'ullWidgetTimeRead',
    $writeWidget = 'ullWidgetTimeWrite',
    $validator = 'ullValidatorTime'
  ;  
  
  protected function configureReadMode()
  {
    $this->columnConfig->removeWidgetOption('fragmentation');
    $this->columnConfig->removeWidgetOption('show_select_box');
    
    $this->addWidget(new $this->readWidget($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    $this->addValidator(new sfValidatorPass());
  }
  
  protected function configureWriteMode()
  {
    if ($this->columnConfig->getWidgetAttribute('size') == null)
    {
      $this->columnConfig->setWidgetAttribute('size', '5');
    }
  	
  	$this->addWidget(new $this->writeWidget($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));      
    $this->addValidator(new $this->validator($this->columnConfig->getValidatorOptions()));
  }
  
  public function getSearchType()
  {
    return 'range';
  }
}