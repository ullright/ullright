<?php 

class UllVentoryItemTypeAttributeColumnConfigCollection extends ullColumnConfigCollection
{

  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    $this['ull_ventory_item_type_id']->setLabel(__('Item type', null, 'ullVentoryMessages'));
    $this['ull_ventory_item_attribute_id']->setLabel(__('Attribute', null, 'ullVentoryMessages'));
    $this['is_presetable']->setLabel(__('Allow saving as preset?', null, 'ullVentoryMessages'));
  }
}