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
    
  }
}