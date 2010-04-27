<?php
/**
 */
class PluginUllCmsItemTable extends UllRecordTable
{
  
 /**
   * Get the menu tree for a given navigation item
   * 
   * @param $parentSlug       Give me the menu item for 
   * @param $currentSlug      Mark the current page
   * @param $depth
   * 
   * @return ullTreeNode
   */
  public static function getMenuTree($parentSlug, $currentSlug = null, $depth = 999999999)
  {
    $item = Doctrine::getTable('UllCmsItem')->findOneBySlug($parentSlug);
    if (!$item)
    {
      throw new InvalidArgumentException("$parentSlug is not a valid ullCmsItem slug");
    }
    
    $tree = self::getSubTree($item, $currentSlug, $depth);
    
    return $tree;
  }
  
  
  /**
   * Get a sub part of a menu
   * 
   * This is used e.g. to render the sidebar menu for an abritrary deep
   * nested sub item.
   * 
   * Example:
   * main_menu
   *   -> about_us
   *      -> team
   *      -> contact
   *      
   * getSubMenuFor('main_menu', 'team') returns team(=active), contact 
   * 
   * @param unknown_type $parentSlug
   * @param unknown_type $currentSlug
   * @param unknown_type $depth
   * @return unknown_type
   */
  public static function getSubMenuFor($parentSlug, $currentSlug, $depth = 999999999)
  {
    $slug = self::findLevel2ItemSlugForParentSlug($parentSlug, $currentSlug);
    
    if ($slug)
    {
      return self::getMenuTree($slug, $currentSlug, $depth);
    }
    // Workaround to display no sub menu for an entry in an invalid menu
    // Example: for page "legal" from the footer menu
    else
    {
      return new ullTreeNode(new UllCmsItem);
    }
  }
  
  
  /**
   * Does the actual work for getSubMenuFor() method
   * 
   * @param $parentSlug
   * @param $currentSlug
   * @return unknown_type
   */
  public static function findLevel2ItemSlugForParentSlug($parentSlug, $currentSlug)
  {
    $item = Doctrine::getTable('UllCmsItem')->findOneBySlug($currentSlug);
    
    if ($item->Parent->slug == $parentSlug)
    {
      return $item->slug;
    }
    elseif ($item->Parent->slug === null)
    {
      return null;
    }    
    else
    {
      return self::findLevel2ItemSlugForParentSlug($parentSlug, $item->Parent->slug);
    }
  }
  
  
  /**
   * Build a menu items tree
   * 
   * @param UllCmsItem $item
   * @param $currentSlug
   * @param $depth
   * @param $hydrate
   * @param $level
   * @return ullTreeNode
   */
  public static function getSubTree(UllCmsItem $item, $currentSlug = null, $depth = 999999999, $hydrate = true, $level = 1)
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