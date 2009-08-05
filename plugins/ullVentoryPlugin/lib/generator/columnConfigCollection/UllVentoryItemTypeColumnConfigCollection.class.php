<?php 

class UllVentoryItemTypeColumnConfigCollection extends ullColumnConfigCollection
{

  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    $this['has_software']->setLabel(__('Has software?', null, 'ullVentoryMessages'));
  }
}