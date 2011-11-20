<?php

class BaseUllMailLoggedMessageColumnConfigCollection extends ullColumnConfigCollection
{
  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    $this['sender']
      ->setWidgetOption('decode_mime', true)
    ;
    
    $this['to_list']
      ->setWidgetOption('decode_mime', true)
    ;
    
    $this['subject']
      ->setWidgetOption('decode_mime', true)
    ;
    
    $this['first_read_at']
      ->setLabel(__('First read at', null, 'ullMailMessages'))
    ;
    
    $this['num_of_readings']
      ->setLabel(__('Read counter', null, 'ullMailMessages'))
    ;    
    
    $this['last_user_agent']
      ->setLabel(__('Last used application', null, 'ullMailMessages'))
    ;      
    
    
    
    foreach($this->getCollection() as $item)
    {
      $item->setAccess('r');
    }
    
  }
}