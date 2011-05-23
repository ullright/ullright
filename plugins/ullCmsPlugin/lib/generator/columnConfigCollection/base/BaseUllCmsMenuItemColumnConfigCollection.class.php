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
    
    $this->disable(array(
      'title', 
      'body', 
      'duplicate_tags_for_search',
      'image',
      'preview_image',
    ));
  }
}