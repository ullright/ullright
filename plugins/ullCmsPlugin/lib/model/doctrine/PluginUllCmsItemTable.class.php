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
    
    // remove invalid current slugs e.g. for other modules than ullCms which use
    // a slug request parameter
    if ($currentSlug !== null && !Doctrine::getTable('UllCmsItem')->findOneBySlug($currentSlug))
    {
      $currentSlug = null;
    }
    
    $tree = self::getSubTree($item, $currentSlug, $depth);
    
    $tree = self::markParentsAsAncestors($tree, $currentSlug);
    
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
   * getSubMenuFor('main_menu', 'team') returns about_us, team(=active), contact 
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
  
  
  /**
   * Get an array of root node slugs
   * 
   * That means cms items that have no parent in the menu tree
   * Examples: main-menu, footer-menu, ...
   * 
   * @return array
   */
  public static function getRootNodeSlugs()
  {
    $q = new ullQuery('UllCmsItem');
    $q
      ->addSelect('slug')
      ->addWhere('parent_ull_cms_item_id IS NULL')
      ->addOrderBy('sequence, name')
    ; 
    
    $results = $q->execute(array(), Doctrine::HYDRATE_NONE);
    
    $return = array();
    
    foreach($results as $result)
    {
      $return[] = $result[0];
    }
    
    return $return;
  }

  /**
   * Build a tree of all ancestors menu items for the given slug
   * 
   * @param string $currentSlug slug of the menu item child
   * @param string $referenceSlug optional: stop at some ancestor level - used for submenus
   * @param ullTreeNode $children optional: give already built subtree by recursion 
   * 
   * @return ullTreeNode tree
   */
  public static function getAncestorTree($currentSlug, $referenceSlug = null, $children = null)
  {
    $item = Doctrine::getTable('UllCmsItem')->findOneBySlug($currentSlug);
    if (!$item)
    {
      throw new InvalidArgumentException('Unknown slug given: ' . $currentSlug);
    }
    
    $node = new UllTreeNode($item);
    
    if ($children)
    {
      if (!$children instanceof ullTreeNode)
      {
        throw new InvalidArgumentException('$children must be a ullTreeNode');
      }
      $children = $node->addSubnode($children);
    }
    {
      $children = $node;
    }
    
    if (
      $currentSlug != $referenceSlug &&
      $parentSlug = $node->getData()->Parent->slug
    )
    {
      $node =  self::getAncestorTree($parentSlug, $referenceSlug, $children);
    }
    
    return $node;
  }

  /**
   * Mark the parents of a given menu tree and given current page slug as
   * ancestors
   * 
   * @param ullTreeNode $tree     The menu tree
   * @param unknown_type $slug    optional The slug of the current page
   *                              Without a given slug, the original tree is 
   *                              returned unchanged.
   * @param unknown_type $ancestorTree  optional ancestor tree for recursion
   */
  public static function markParentsAsAncestors(ullTreeNode $tree, $slug = null, $ancestorTree = null)
  {
    if (!$slug)
    {
      return $tree;
    }
    
    if (!$ancestorTree)
    {
      $ancestorTree = self::getAncestorTree($slug, $tree->getData()->slug);
    }
    
    if ($tree->getData()->slug == $slug)
    {
      return $tree;
    }
    
    $tree->setMeta('is_ancestor', true);
    
    foreach ($tree->getSubnodes() as $subnode)
    {
      if (
        $subnode->getData()->id == $ancestorTree->getFirstSubnode()->getData()->id
      )
      {
        self::markParentsAsAncestors($subnode, $slug, $ancestorTree->getFirstSubnode());
      }
    }
    
    return $tree;
  }
  
}
