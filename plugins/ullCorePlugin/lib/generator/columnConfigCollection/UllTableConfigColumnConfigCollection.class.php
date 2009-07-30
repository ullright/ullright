<?php

class UllTableConfigColumnConfigCollection extends UllEntityColumnConfigCollection
{
  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    $this['sort_columns']->setLabel(__('Order by columns', null, 'ullCoreMessages'));
    $this['search_columns']->setLabel(__('Search in columns', null, 'ullCoreMessages'));
    
    if ($this->isListAction())
    {
      $this->disable(array('description'));
    } 
  }
}