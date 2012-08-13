<?php 

class BaseUllCmsMenuItemColumnConfigCollection extends UllCmsItemColumnConfigCollection
{

  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    parent::applyCustomSettings();
    
    $this['is_active']
      ->setAjaxUpdate(true);
    ;
    
    $this->disable(array(
      'title', 
      'body', 
      'image',
      'preview_image',
      'gallery',
      'duplicate_tags_for_search',
      'ull_cms_content_type_id'
    ));
  }
}