<?php 

class BaseUllNavigationItemColumnConfigCollection extends ullColumnConfigCollection
{

  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    $this['parent_ull_navigation_item_id']
      ->setLabel(__('In navigation', null, 'ullCmsMessages'))
    ;
    
  }
}