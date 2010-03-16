<?php

class ullTreeNavigationRenderer
{
  protected
    $node
  ;
  
 
  public function __construct(ullTreeNode $node)
  {
    $this->node = $node;
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
    
    $return .= '<ul class="ull_navigation_' . ullCoreTools::htmlId($node->getData()->slug) . '">' . "\n";
    
    if ($node->hasSubnodes())
    {
      foreach ($node->getSubnodes() as $subNode)
      { 
        $return .= '<li ' . (($subNode->hasMeta('is_current')) ? 'class="ull_navigation_is_current"' : '') . '>';
        $return .= '<a href="' . url_for('ull_cms_show', $subNode->getData()) . '">';
        $return .= $subNode->getData()->name;
        $return .= '</a>';
        if ($subNode->hasSubnodes())
        {
          $return .= "\n" . $this->doRendering($subNode) . "\n";
        }
        $return .= '</li>' . "\n";
      }        
    }
    
    $return .= '</ul>' . "\n";
      
    return $return;
  }

}
