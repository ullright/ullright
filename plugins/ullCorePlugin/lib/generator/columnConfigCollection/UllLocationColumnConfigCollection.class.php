<?php

class UllLocationColumnConfigCollection extends ullColumnConfigCollection
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
    
    
    if ($this->isShowAction())
    {
      $this->disable(array(
        'short_name', 
        'phone_base_no',
        'fax_base_no',      
        'phone_default_extension',
        'fax_default_extension',
      ));
      
      $this->order(array(
        'name',
        'street',
        'post_code',
        'city',
        'country',
      ));
    }    
  }
}