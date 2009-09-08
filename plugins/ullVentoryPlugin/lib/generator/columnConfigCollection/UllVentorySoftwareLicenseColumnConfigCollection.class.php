<?php 

class UllVentorySoftwareLicenseColumnConfigCollection extends ullColumnConfigCollection
{

  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    $this['ull_ventory_software_id']->setLabel(__('Software', null, 'ullVentoryMessages'));
    $this['license_key']->setLabel(__('License key', null, 'ullVentoryMessages'));
    $this['quantity']->setLabel(__('Quantity', null, 'ullVentoryMessages'));
    $this['supplier']->setLabel(__('Supplier', null, 'ullVentoryMessages'));
    $this['delivery_date']
      ->setLabel(__('Delivery date', null, 'ullVentoryMessages'))
      ->setMetaWidgetClassName('ullMetaWidgetDate')
    ;
  }
}