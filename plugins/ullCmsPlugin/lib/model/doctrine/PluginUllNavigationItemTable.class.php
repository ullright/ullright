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