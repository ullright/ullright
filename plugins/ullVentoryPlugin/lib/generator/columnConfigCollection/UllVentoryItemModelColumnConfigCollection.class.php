<?php 

class UllVentoryItemModelColumnConfigCollection extends ullColumnConfigCollection
{
  
  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    $this['ull_ventory_item_manufacturer_id']->setLabel(__('Item manufacturer', null, 'ullVentoryMessages'));
    $this['ull_ventory_item_type_id']->setLabel(__('Item type', null, 'ullVentoryMessages'));
  }
}