<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class UllVentoryItemTypeTable extends PluginUllVentoryItemTypeTable
{
  
  static public function findChoicesIndexedBySlug()
  {
    $lang = substr(sfContext::getInstance()->getUser()->getCulture(), 0, 2);
    
    $q = new Doctrine_Query;
    $q
      ->from('UllVentoryItemType t, t.Translation trans')
      ->where('trans.lang = ?', $lang)
      ->orderBy('trans.name')
    ;
    $results = $q->execute(array(), Doctrine::HYDRATE_ARRAY);
    
    $return = array();
    
    foreach($results as $type)
    {
      $return[$type['slug']] = $type['Translation'][$lang]['name'];
    }
    
    return $return;
  }

}