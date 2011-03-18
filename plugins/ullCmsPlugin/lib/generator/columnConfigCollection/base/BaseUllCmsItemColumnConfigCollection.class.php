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
    
    $this['name']
      ->setLabel(__('Menu title', null, 'ullCmsMessages'))
    ;
    
    $this['full_path']
      ->setLabel(__('Menu entry', null, 'ullCmsMessages'))
    ;
    
    $this['parent_ull_cms_item_id']
      ->setMetaWidgetClassName('ullMetaWidgetCmsParentItem')
      ->setLabel(__('Higher menu entry', null, 'ullCmsMessages'))
      ->setWidgetOption('add_empty', true)
      ->setWidgetOption('show_search_box', true)      
    ;
    
  }
}