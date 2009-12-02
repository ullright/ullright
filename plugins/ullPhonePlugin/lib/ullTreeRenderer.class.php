<?php

class ullTreeRenderer
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
    return $this->doRendering($this->node);
  }
  
  public function doRendering($node)
  {
    $return = ullTreeRenderer::renderBox($node->getData());
    
    if ($node->hasSubnodes())
    {
      foreach($node->getSubnodes() as $subnode)
      {
        $return .= $this->doRendering($subnode);        
      }
    }
    
    return $return;
  }
  
  
  public static function renderBox($content, $cssClass = 'foo')
  {
    return '<div class="' . $cssClass . '">' . $content . '</div>'; 
  }
  
  
}