<?php

/**
 * PluginUllNavigationItem
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 6820 2009-11-30 17:27:49Z jwage $
 */
abstract class PluginUllNavigationItem extends BaseUllNavigationItem
{
  
  /**
   * Get the sub items for current item
   *
   * @param boolean $hydration
   * @return mixed
   */
  public function getSubs($hydrationMode = null)
  {
    $q = new ullQuery('UllNavigationItem');
    $q
      ->addSelect(array('slug', 'name'))
      ->addWhere('parent_ull_navigation_item_id = ?', $this->id)
      ->addOrderby('name')
    ;
  
    $result = $q->execute(null, $hydrationMode);
  
    return $result;
  }  

}