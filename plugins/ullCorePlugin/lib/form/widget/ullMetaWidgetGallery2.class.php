<?php
/**
 * ullMetaWidgetGallery2
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
      $path = sfConfig::get('sf_upload_dir') . '/tableTool/' .
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
    
    
  }
  
  
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/form/widget/ullMetaWidget#configureWriteMode()
   */
  protected function configureWriteMode()
  {
    $this->columnConfig->setWidgetOption('model', $this->columnConfig->getModelName());
    $this->columnConfig->setWidgetOption('column', $this->columnConfig->getColumnName());
    $this->columnConfig->setWidgetOption('columns_config_class', 
      $this->columnConfig->getColumnsConfigClass());
        
    
    
    $this->addWidget(new ullWidgetGalleryWrite2(
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
