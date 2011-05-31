<?php
/**
 * TableConfiguration for UllVentoryItem
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class BaseUllVentoryItemTableConfiguration extends ullTableConfiguration
{
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
    $this
      ->setListColumns(array(
        'inventory_number',
        'artificial_toggle_inventory_taking',
        'UllVentoryItemModel->UllVentoryItemType->name',
        'UllVentoryItemModel->UllVentoryItemManufacturer->name',
        'UllVentoryItemModel->name',
// we need to fix the UllEntity->display_name translation problem (ullVentoryDummyUsers) before we can use this
//        'UllEntity->display_name',
        'ull_entity_id',
        'UllEntity->UllLocation->name',
        'serial_number',
//        'Updator->display_name',
//        'updated_at',
      ))
      
      // relation handling is not implemented yet for edit mode
//      ->setEditColumns(array(
//        'UllVentoryItemModel->ull_ventory_item_type_id',
//        'UllVentoryItemModel->ull_ventory_item_manufacturer_id',
//        'ull_ventory_item_model_id',
//        'inventory_number',
//        'serial_number',
//        'comment',
//      ))
      ->setOrderBy('updated_at desc, id desc')
      ->setSearchColumns(array(
        'inventory_number',
        'UllVentoryItemModel->UllVentoryItemType->name',
        'UllVentoryItemModel->UllVentoryItemManufacturer->name',
        'UllVentoryItemModel->name',      
        'serial_number', 
        'comment'
      ))
      ->setCustomRelationName('UllEntity', 
        __('Owner', null, 'common'))
      ->setCustomRelationName('UllEntity->UllLocation', 
        __('Owner', null, 'common') . ' ' . __('Location', null, 'common'))
      ->setCustomRelationName('UllVentoryItemModel->UllVentoryItemType', 
        __('Type', null, 'ullVentoryMessages'))
      ->setCustomRelationName('UllVentoryItemModel->UllVentoryItemManufacturer', 
      __('Manufacturer', null, 'ullVentoryMessages'))
    ;
  }
  
}