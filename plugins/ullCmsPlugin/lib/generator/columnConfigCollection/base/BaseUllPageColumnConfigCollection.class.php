<?php 

class BaseUllPageColumnConfigCollection extends ullColumnConfigCollection
{

  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    $this['ull_navigation_item_id']
      ->setLabel(__('In navigation', null, 'ullCmsMessages'))
    ;
    
    $this['body']
      ->setMetaWidgetClassName('ullMetaWidgetFCKEditor')
    ;
    
    if ($this->isListAction())
    {
//      $this->disable(array(
//        'body',
//        'ull_navigation_item_id'
//      ));
      
    }
    
  }
}