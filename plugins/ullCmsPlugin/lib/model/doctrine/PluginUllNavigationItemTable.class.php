<?php
/**
 */
class PluginUllNavigationItemTable extends UllRecordTable
{
  
  /**
   * Get the navigation tree for a given navigation item
   * 
   * @param $parentSlug       Give me the navigaiton item for 
   * @param $currentSlug      Mark the current page
   * @param $depth
   * 
   * @return ullTreeNode
   */
  public static function getNavigationTree($parentSlug, $currentSlug = null, $depth = 999999999)
  {
    $item = Doctrine::getTable('UllNavigationItem')->findOneBySlug($parentSlug);
    
    $tree = self::getSubTree($item, $currentSlug, $depth);
    
    return $tree;
  }
  
  
  /**
   * Get a sub part of a navigation
   * 
   * This is used e.g. to render the sidebar navigation for an abritrary deep
   * nested sub item.
   * 
   * Example:
   * main_navigation
   *   -> about_us
   *      -> team
   *      -> contact
   *      
   * getSubNavigationFor('main_navigation', 'team') returns team(=active), contact 
   * 
   * @param unknown_type $parentSlug
   * @param unknown_type $currentSlug
   * @param unknown_type $depth
   * @return unknown_type
   */
  public static function getSubNavigationFor($parentSlug, $currentSlug, $depth = 999999999)
  {
    $slug = self::findLevel2ItemSlugForParentSlug($parentSlug, $currentSlug);
    
    return self::getNavigationTree($slug, $currentSlug, $depth);
  }
  
  public static function findLevel2ItemSlugForParentSlug($parentSlug, $currentSlug)
  {
//        var_dump($parentSlug);
//    var_dump($currentSlug);
    
    $item = Doctrine::getTable('UllNavigationItem')->findOneBySlug($currentSlug);
    
//    var_dump($item->toArray());
//    var_dump($item->Parent->toArray());
    
    if ($item->Parent->slug == $parentSlug)
    {
      return $item->slug;
    }
    else
    {
      return self::findLevel2ItemSlugForParentSlug($parentSlug, $item->Parent->slug);
    }
  }
  
  
  /**
   * Build navigation item tree
   *
   * @param UllEntity $entity
   * @param integer $depth
   * @param boolean $hydrate
   * @param integer $level
   * @return ullTreeNode
   */
  public static function getSubTree(UllNavigationItem $item, $currentSlug = null, $depth = 999999999, $hydrate = true, $level = 1)
  {
    $node = new ullTreeNode(($hydrate) ? $item : $item->slug);
    
    if ($currentSlug == $item->slug)
    {
      $node->setMeta('is_current', true);
    }
    else
    {
      $node->setMeta('is_current', false);
    }

    if (($subs = $item->getSubs()) && ($level < $depth))
    {
      $level++;

      foreach ($subs as $sub)
      {
        $node->addSubnode(self::getSubTree($sub, $currentSlug, $depth, $hydrate, $level));
      }
    }
    return $node;
  }  

}