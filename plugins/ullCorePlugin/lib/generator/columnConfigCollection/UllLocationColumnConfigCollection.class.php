<?php

class UllLocationColumnConfigCollection extends UllEntityColumnConfigCollection
{
  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    $this['country']->setMetaWidgetClassName('ullMetaWidgetCountry');
    
    if ($this->isListAction())
    {
      $this->disableAllExcept(array('id', 'name', 'short_name', 'city', 'country'));
    } 
  }
}