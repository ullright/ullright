<?php

/**
 * Provides drop-in inline editing for html content using content elements like
 * "text with image", "gallery", etc
 * 
 * @author klemens
 *
 */
class ullMetaWidgetContentElements extends ullMetaWidget
{
  protected function configureWriteMode($withValidator = true)
  {
    $this->addWidget(new ullWidgetContentElementsWrite(
      $this->columnConfig->getWidgetOptions(), 
      $this->columnConfig->getWidgetAttributes()
    ));
    $this->addValidator(new sfValidatorString(
      $this->columnConfig->getValidatorOptions()
    ));
  }
  
  protected function configureReadMode()
  {
    $this->columnConfig->removeWidgetOption('element_types');
    
    parent::configureReadMode();
  }

}