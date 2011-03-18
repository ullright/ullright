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
      'full_path', 'type', 'preview_image', 'image'
    ));
    
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
    
    // Enable in your custom column config if you like to use it
    
//    $this['preview_image']
//      ->setMetaWidgetClassName('ullMetaWidgetSimpleUpload')
//      ->setLabel(__('Preview image', null, 'ullCmsMessages'))
//      ->setValidatorOption(
//          'imageWidth', 
//          sfConfig::get('app_ull_cms_preview_image_width', 140)
//        )
//    ;
//    $this['image']
//      ->setMetaWidgetClassName('ullMetaWidgetSimpleUpload')
//      ->setLabel(__('Image', null, 'ullCmsMessages'))
//      ->setValidatorOption(
//          'imageWidth', 
//          sfConfig::get('app_ull_cms_image_width', 640)
//        )
//    ;    
    
    
  }
}