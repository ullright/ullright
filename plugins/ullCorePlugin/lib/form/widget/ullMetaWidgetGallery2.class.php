<?php
/**
 * ullMetaWidgetGallery
 * 
 * New, improved version.
 *
 * Used for multi file image upload and thumbnail creation
 * 
 * Also supports single file upload
 */
class ullMetaWidgetGallery extends ullMetaWidget
{

  /**
   * Configuration
   */
  protected function configure()
  {
    if (!$path = $this->columnConfig->getOption('path'))
    {
      // Note: sfConfig::get('sf_upload_dir') is prepended later on
      // to avoid path tampering during the ajax call
      $path =  '/tableTool/' .
        $this->columnConfig->getModelName() . '/' .
        $this->columnConfig->getColumnName()
      ;
    }
    $this->columnConfig->setOption('path', $path);    

    if (!$this->columnConfig->getOption('allow_multi'))
    {
      $this->columnConfig->setOption('allow_multi', true);
    }    
    if (!$this->columnConfig->getOption('mime_types'))
    {
      $this->columnConfig->setOption('mime_types', 'web_images');
    }
    if (!$this->columnConfig->getOption('image_width'))
    {
      $this->columnConfig->setOption('image_width', 640);
    }
    if (!$this->columnConfig->getOption('image_height'))
    {
      $this->columnConfig->setOption('image_height', 480);
    }
    if (!$this->columnConfig->getOption('create_thumbnails'))
    {
      $this->columnConfig->setOption('create_thumbnails', true);
    }     
    if (!$this->columnConfig->getOption('thumbnail_width'))
    {
      $this->columnConfig->setOption('thumbnail_width', 160);
    }
    if (!$this->columnConfig->getOption('thumbnail_height'))
    {
      $this->columnConfig->setOption('thumbnail_height', 120);
    }
    
    // Pass options to widget
    $this->columnConfig->setWidgetOption('config', $this->columnConfig->getOptions());
  }
  
  
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/form/widget/ullMetaWidget#configureWriteMode()
   */
  protected function configureWriteMode()
  {
    $this->addWidget(new ullWidgetGalleryWrite(
      $this->columnConfig->getWidgetOptions(), 
      $this->columnConfig->getWidgetAttributes()
    ));
    $this->addValidator(new sfValidatorString($this->columnConfig->getValidatorOptions()));
  }
  
  
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/form/widget/ullMetaWidget#configureReadMode()
   */
//  protected function configureReadMode()
//  {
//    $this->addWidget(new ullWidgetGalleryRead(
//      array_merge($this->columnConfig->getWidgetOptions(), array('path' => $this->path)), 
//      $this->columnConfig->getWidgetAttributes()
//    ));
//    $this->addValidator(new sfValidatorPass());    
//  }  
}
