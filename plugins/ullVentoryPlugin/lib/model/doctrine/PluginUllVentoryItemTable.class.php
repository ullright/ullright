<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PluginUllVentoryItemTable extends UllRecordTable
{
  
  /**
   * Method to return new object for the create route
   * TODO: is there a better (=built in) way to do this?
   *  
   * @return UllVentoryItem
   */
  public static function getNew()
  {
    return new UllVentoryItem;    
  }
  
  /**
   * Find id by inventory_number
   * 
   * @param $inventoryNumber
   * @return integer / false
   */
  public static function findIdByInventoryNumber($inventoryNumber)
  {
    $q = new Doctrine_Query;
    $q
      ->select('i.id')
      ->from('UllVentoryItem i')
      ->where('i.inventory_number = ?', $inventoryNumber)
      ->useResultCache(true)
    ;
    $r = $q->fetchOne(null, Doctrine::HYDRATE_NONE);
    
    return ($r) ? $r[0] : false;
  }
  
  public static function findByTypeSlug($typeSlug)
  {
    $q = new Doctrine_Query;
    $q
      ->from('UllVentoryItem i, i.UllVentoryItemModel m, m.UllVentoryItemType t')
      ->where('t.slug = ?', $typeSlug)
      ->useResultCache(true)
    ;
    return $q->execute();
  }
}