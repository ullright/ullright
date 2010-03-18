<?php 

class BaseUllPageColumnConfigCollection extends ullColumnConfigCollection
{

  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    if ($this->isListAction())
    {
//      $this->disable(array(
//        'body',
//        'ull_navigation_item_id'
//      ));
      
    }
    
  }
}