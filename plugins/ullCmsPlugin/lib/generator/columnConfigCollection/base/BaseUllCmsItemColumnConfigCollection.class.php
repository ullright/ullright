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
    
    $this['full_path']
      ->setLabel(__('Menu entry', null, 'ullCmsMessages'))
    ;
    
    $this['parent_ull_cms_item_id']
      ->setLabel(__('Higher menu entry', null, 'ullCmsMessages'))
      ->setWidgetOption('add_empty', true)
    ;
    
  }
}