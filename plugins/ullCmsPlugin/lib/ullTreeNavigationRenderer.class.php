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
        
        $return .= ($uri) ? '<a href="' . $uri . '">' : '';
        $return .= $subNode->getData()->name;
        $return .= ($uri) ? '</a>' : '';
        
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

}
