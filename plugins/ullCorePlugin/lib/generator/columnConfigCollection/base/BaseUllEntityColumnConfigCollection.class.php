<?php 

class BaseUllEntityColumnConfigCollection extends ullColumnConfigCollection
{
  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    $this->disable(array('version'));
    
    $this['display_name']->setMetaWidgetClassName('ullMetaWidgetUllEntity');

    $this['email']
      ->setMetaWidgetClassName('ullMetaWidgetEmail')      
    ;
    
    $this['type']
      ->setMetaWidgetClassName('ullMetaWidgetUllEntityType')
    ;

  }
}