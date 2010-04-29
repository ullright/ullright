<?php
/**
 * ullMetaWidgetSimpleUpload
 *
 * Used for uploads for ullTableTool module
 */
class ullMetaWidgetNewsLink extends ullMetaWidget
{
  
  
  
  
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/form/widget/ullMetaWidget#configureWriteMode()
   */
  protected function configureWriteMode()
  {
    if (!$this->columnConfig->getWidgetAttribute('size'))
    {
      $this->columnConfig->setWidgetAttribute('size', '40');
    }
    $this->addWidget(
      new ullWidgetNewsLinkWrite($this->columnConfig->getWidgetOptions(), 
      $this->columnConfig->getWidgetAttributes()
    ));
    $this->addValidator(new sfValidatorString($this->columnConfig->getValidatorOptions()));
   
  }
}
