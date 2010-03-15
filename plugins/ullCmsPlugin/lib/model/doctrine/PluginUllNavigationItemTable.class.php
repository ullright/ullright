<?php
/**
 */
class PluginUllNavigationItemTable extends UllRecordTable
{
  
  
  public static function getNavigationArray($parentSlug = null, $currentSlug = null)
  {
    $q = new Doctrine_Query;
    $q
      ->from('UllNavigationItem n')
      ->where('n.slug = ?', 'main-navigation')
    ;
    
    $result = $q->execute(array(), DOCTRINE::HYDRATE_ARRAY);
    
    var_dump($result);
  }

}