<?php 

class BaseUllCmsMenuItemColumnConfigCollection extends ullColumnConfigCollection
{

  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    $this['parent_ull_cms_item_id']
      ->setLabel(__('In navigation', null, 'ullCmsMessages'))
    ;
    
  }
}