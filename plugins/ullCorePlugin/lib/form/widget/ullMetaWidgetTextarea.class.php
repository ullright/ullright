<?php
/**
 * ullMetaWidgetTextarea
 *
 * Used for strings
 */
class ullMetaWidgetTextarea extends ullMetaWidgetString
{
  
  protected function configureReadMode()
  {
    $this->columnConfig->removeWidgetOption('rows');
    $this->columnConfig->removeWidgetOption('cols');
    
    $this->addWidget(new ullWidgetTextarea($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    $this->addValidator(new sfValidatorPass());  
  }
  
  protected function configureWriteMode()
  {
    $this->columnConfig->removeWidgetOption('decode_mime');
    
    if ($cols = $this->columnConfig->getWidgetOption('cols'))
    {
      $this->columnConfig->setWidgetAttribute('cols', $cols);
      $this->columnConfig->removeWidgetOption('cols');
    }
    
    if ($rows = $this->columnConfig->getWidgetOption('rows'))
    {
      $this->columnConfig->setWidgetAttribute('rows', $rows);
      $this->columnConfig->removeWidgetOption('rows');
    }    
    
    
    if ($this->columnConfig->getWidgetAttribute('cols') == null)
    {
      $this->columnConfig->setWidgetAttribute('cols', '58');        
    }
    if ($this->columnConfig->getWidgetAttribute('rows') == null)
    {
      $this->columnConfig->setWidgetAttribute('rows', '4');        
    }     
    
    $this->addWidget(new sfWidgetFormTextarea($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    $this->addValidator(new sfValidatorString($this->columnConfig->getValidatorOptions()));  
  }
  
  protected function configureSearchMode()
  {
    parent::configureWriteMode();
  }
  
}