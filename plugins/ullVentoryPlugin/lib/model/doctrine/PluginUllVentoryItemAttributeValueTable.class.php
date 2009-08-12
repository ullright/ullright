<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PluginUllVentoryItemAttributeValueTable extends UllRecordTable
{
  
  public static function findByItemIdAndTypeAttributeId($itemId, $typeAttributeId)
  {
    $q = new Doctrine_Query;
    $q
      ->from('UllVentoryItemAttributeValue x')
      ->where('x.ull_ventory_item_id = ?', $itemId)
      ->addWhere('x.ull_ventory_item_type_attribute_id = ?', $typeAttributeId)
    ;
    return $q->execute()->getFirst();   
  }
  
  public static function findByItemIdAndTypeAttributeIdOrCreate($itemId, $typeAttributeId)
  {
    $itemAttributeValue = UllVentoryItemAttributeValueTable::findByItemIdAndTypeAttributeId($itemId, $typeAttributeId);
    
    if (!$itemAttributeValue)
    {
      $itemAttributeValue = new UllVentoryItemAttributeValue; 
      $itemAttributeValue->ull_ventory_item_id = $itemId;
      $itemAttributeValue->ull_ventory_item_type_attribute_id = $typeAttributeId;
    }
    
    return $itemAttributeValue; 
  }  

}