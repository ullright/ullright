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
      ->useResultCache(true);
    ;
    return $q->fetchOne()->name;
  }
  
  public static function findHelpByItemTypeAttributeId($id)
  {
    $q = new Doctrine_Query;
    $q
      ->from('UllVentoryItemAttribute a')
      ->where('a.UllVentoryItemTypeAttribute.id = ?', $id)
      ->useResultCache(true);
    ;
    return $q->fetchOne()->help;
  }
  
  public static function findByNameAndItemTypeSlug($name, $itemTypeSlug, $lang = null)
  {
    if ($lang == null)
    {
      $lang = substr(sfContext::getInstance()->getUser()->getCulture(), 0, 2);
    }
    
    $q = new Doctrine_Query;
    $q
      ->select('a.id')
      ->from('UllVentoryItemAttribute a, a.Translation tr, a.UllVentoryItemTypeAttribute ta, ta.UllVentoryItemType t')
      ->where('tr.name = ?', $name)
      ->addWhere('tr.lang = ?', $lang)
      ->addWhere('t.slug = ?', $itemTypeSlug)
      ->useResultCache(true)
    ;
    $result = $q->fetchOne(null, Doctrine::HYDRATE_NONE);
    
    // re-query to get all translations
    return Doctrine::getTable('UllVentoryItemAttribute')->findOneById($result[0]);
  }

}