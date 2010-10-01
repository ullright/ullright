<?php

class ullTreeMenuRenderer
{
  protected
    $node,
    $renderUlTag
  ;
  
  /**
   * Returns a new ullTreeNavigationRenderer instance,
   * based on a starting ullTreeNode.
   * 
   * Renders nodes as unordered lists with each subnode enclosed
   * by a list element tag. For the first level of nodes (main level)
   * the enclosing ul-tag can be prevented by setting $renderUlTag to
   * false 
   * 
   * @param ullTreeNode $node the node which should be rendered
   * @param unknown_type $renderUlTag if false, the first level of nodes is not enclosed by ul tags
   */
  public function __construct(ullTreeNode $node, $renderUlTag = true)
  {
    $this->node = $node;
    $this->renderUlTag = (boolean) $renderUlTag;
  }
  
  
  public function __toString()
  {
    return (string) $this->render();
  }
  
  
  public function render()
  {
    $return = '';

    $return .= $this->doRendering($this->node);
    
    $return .= $this->renderMenuExpandJavascript($this->node->getData()->slug);
    
    return $return;
  }
  
  
  public function doRendering(ullTreeNode $node)
  {
    $return = '';
    
    if ($this->renderUlTag || $node->getLevel() >= 2)
    {
      $return .= '<ul class="ull_menu_' . ullCoreTools::htmlId($node->getData()->slug) . '">' . "\n";
    }
    
    if ($node->hasSubnodes())
    {
      foreach ($node->getSubnodes() as $subNode)
      { 
        $return .= '<li' . (($subNode->hasMeta('is_current')) ? ' class="ull_menu_is_current"' : '') . '>';
        
        $uri = null;
        
        if ($subNode->getData()->type == 'page')
        {
          $uri = url_for('ull_cms_show', $subNode->getData());
        }
        //type is 'menu_item', since atm there are only these two options
        else if ($subNode->getData()->link)
        {
          $uri = url_for($subNode->getData()->link);
        }
        
        $return .= ($uri) ? '<a href="' . $uri . '" class="ull_menu_entry_' . ullCoreTools::htmlId($subNode->getData()->slug) . '">' : '<a href="#" class="ull_menu_non_clickable">';
        $return .= $subNode->getData()->name;
        $return .= '</a>';
        
        if ($subNode->hasSubnodes())
        {
          $return .= "\n" . $this->doRendering($subNode) . "\n";
        }
        
        $return .= '</li>' . "\n";
      }        
    }
    
    if ($this->renderUlTag || $node->getLevel() >= 2)
    {
      $return .= '</ul>' . "\n";
    }
      
    return $return;
  }
  
  /**
   * Render javascript code to hide/expand menu levels
   * 
   * @param string $menuRootSlug Slug of the root element. E.g. "main-menu"
   * @return string
   */
  protected function renderMenuExpandJavascript($menuRootSlug)
  {
    $menuRootSlug = ullCoreTools::htmlId($menuRootSlug);
    $pageSlug = ullCoreTools::htmlId(sfContext::getInstance()->getRequest()->getParameter('slug'));
    
    // Supply a config option like the following in app.yml to enable hiding/expanding
    $expandUpToLevel = sfConfig::get('app_ull_cms_' . $menuRootSlug . '_expand_up_to_level');
    
    if (!$expandUpToLevel)
    {
      return '';
    }

    $depthSelector = '';
    while ($expandUpToLevel--)
    {
      $depthSelector .= ' ul';
    }
    
    $return = javascript_tag('
// hide all entries except the number of configured levels
$("ul.ull_menu_' . $menuRootSlug . $depthSelector . '").hide();  

// make non-pages (menu only entries) clickable
$("ul.ull_menu_' . $menuRootSlug . ' a[href=\'#\']").each(function(index) {

  $(this).click(function () {
    // hide all entries except the number of configured levels
    $("ul.ull_menu_' . $menuRootSlug . $depthSelector . '").hide();
    // show the current entries\' parents
    $(this).parents("ul").show();
    // show the current entry\'s children
    $(this).next().show();
  });

});

');
    
    // Render only if a cms page is shown
    if ($pageSlug)
    {
      $return .= javascript_tag('
// show sub entries for selected page
$("ul.ull_menu_' . $pageSlug . '").show();

// show current tree up to the root
$(".ull_menu_entry_' . $pageSlug . '").parents("ul").show();

');
    }

    return $return;
  }

}
