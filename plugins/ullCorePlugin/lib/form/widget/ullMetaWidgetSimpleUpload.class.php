<?php
/**
 * ullMetaWidgetSimpleUpload
 *
 * Used for uploads for ullTableTool module
 */
class ullMetaWidgetSimpleUpload extends ullMetaWidget
{
  protected $path;

  
  protected function configure()
  {
    $this->columnConfig->removeValidatorOption('max_length');

    if (!$this->columnConfig->getValidatorOption('path'))
    {
      $uploadPath = sfConfig::get('sf_upload_dir') . '/tableTool/' . get_class($this->getForm()->getObject());
      $this->columnConfig->setValidatorOption('path', $uploadPath);
    }
    $this->path = $this->columnConfig->getValidatorOption('path');
    
    if (!$this->columnConfig->getValidatorOption('mime_types'))
    {
      $this->columnConfig->setValidatorOption('mime_types', 'web_images');
    }
    if (!$this->columnConfig->getValidatorOption('imageWidth'))
    {
      $this->columnConfig->setValidatorOption('imageWidth', 1000);
    }
  if (!$this->columnConfig->getValidatorOption('imageHeight'))
    {
      $this->columnConfig->setValidatorOption('imageHeight', 1000);
    }
  }
  

  
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/form/widget/ullMetaWidget#configureReadMode()
   */
  protected function configureReadMode()
  {
    $this->addWidget(new ullWidgetSimpleUploadRead(
     array_merge($this->columnConfig->getWidgetOptions(), array('path' => $this->path)), 
     $this->columnConfig->getWidgetAttributes()
    ));
    $this->addValidator(new sfValidatorPass());    
  }
  
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/form/widget/ullMetaWidget#configureWriteMode()
   */
  protected function configureWriteMode()
  {
    $this->addWidget(new ullWidgetSimpleUploadWrite(
      array_merge($this->columnConfig->getWidgetOptions(), array('path' => $this->path)), 
      $this->columnConfig->getWidgetAttributes()
    ));
    $this->addValidator(new ullValidatorFile($this->columnConfig->getValidatorOptions()));
   
  }
}
