<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PluginUllVentoryItemAttributeTable extends UllRecordTable
{
  
  public static function findNameByItemTypeAttributeId($id)
  {
    $q = new Doctrine_Query;
    $q
      ->from('UllVentoryItemAttribute a')
      ->where('a.UllVentoryItemTypeAttribute.id = ?', $id)
    ;
    return $q->execute()->getFirst()->name;
  }
  
  public static function findHelpByItemTypeAttributeId($id)
  {
    $q = new Doctrine_Query;
    $q
      ->from('UllVentoryItemAttribute a')
      ->where('a.UllVentoryItemTypeAttribute.id = ?', $id)
    ;
    return $q->execute()->getFirst()->help;
  }  

}