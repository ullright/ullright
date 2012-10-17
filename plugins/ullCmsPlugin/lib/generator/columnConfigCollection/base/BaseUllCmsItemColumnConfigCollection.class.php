<?php 

class BaseUllCmsItemColumnConfigCollection extends ullColumnConfigCollection
{

  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    $this->disable(array(
      'full_path', 'type'
    ));
    
    $this['is_active']
      ->setAjaxUpdate(true);
    ;    
    
    $this['name']
      ->setLabel(__('Menu title', null, 'ullCmsMessages'))
    ;
    
    $this['full_path']
      ->setLabel(__('Menu entry', null, 'ullCmsMessages'))
    ;
    
    $this['parent_ull_cms_item_id']
      ->setMetaWidgetClassName('ullMetaWidgetCmsParentItem')
      ->setLabel(__('Parent', null, 'ullCmsMessages'))
      ->setWidgetOption('add_empty', true)
      ->setWidgetOption('show_search_box', true)      
    ;
    
    $this['sequence']
      ->setHelp(__(
        'Sort the items. Enter integer numbers. Entries with higher numbers are listed behind those with lower numbers. Use steps of 100 to allow inserting items in between.'
        , null, 'ullCoreMessages')
      )
    ;
    
    $this['ull_cms_content_type_id']
      ->setLabel(__('Content type', null, 'ullCmsMessages'))
    ;
    
    $this['preview_image']
      ->setMetaWidgetClassName('ullMetaWidgetSimpleUpload')
      ->setLabel(__('Preview image', null, 'ullCmsMessages'))
      ->setValidatorOption(
          'imageWidth', 
          sfConfig::get('app_ull_cms_preview_image_width', 140)
        )
    ;
    
    $this['image']
      ->setMetaWidgetClassName('ullMetaWidgetSimpleUpload')
      ->setLabel(__('Image', null, 'ullCmsMessages'))
      ->setValidatorOption(
          'imageWidth', 
          sfConfig::get('app_ull_cms_image_width', 640)
        )
    ;    
    
    $this['gallery']
      ->setLabel(__('Gallery', null, 'ullCmsMessages'))
      ->setMetaWidgetClassName('ullMetaWidgetGallery')
      ->setOption('image_width', 450)
      ->setOption('image_height', 300)
      ->setOption('generate_thumbnails', true)
      ->setOption('thumbnail_width', 150)
      ->setOption('thumbnail_height', 100)      
      ->setHelp(__('Images are automatically resized. Drag images around to sort.', 
        null, 'ullCmsMessages'))
    ;       
    
    if ($this->isCreateAction())
    {
      $this['ull_cms_content_type_id']
        ->disable()
      ;      
    }    
    
    if ($this->isEditAction())
    {
      $this['ull_cms_content_type_id']
        ->markAsAdvancedField(true)
      ;      
    }
    
  }
}