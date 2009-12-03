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
  
  public function doRendering(ullTreeNode $node)
  {
    $return = '';
    
//    if ($node->hasSubnodes())
//    {
      $cssClass = 'ull_orgchart_superior';
//    }
//    else
//    {
//      $cssClass = 'ull_orgchart_subordinate';
//    }

    if (!$node->isRightMost())
    {
      $cssClass .= ' inline-block';
    }  
      
    $return .= ullTreeRenderer::renderBox($node->getData(), $cssClass);
    
//    $return .= ullTreeRenderer::renderSpacer('all');
    
    if ($node->hasSubnodes())
    {
      foreach($node->getSubnodes() as $subnode)
      {
        $return .= $this->doRendering($subnode);        
      }
    }
    
    return $return;
  }
  
  
  public static function renderBox($content, $cssClass)
  {
    return '<div class="ull_orgchart_box ' . $cssClass . '">' . $content . '</div>'; 
  }
  
  public static function renderSpacer($type)
  {
    switch ($type)
    {
      case 'all':
        $topLeft = 'ull_orgchart_spacer_right_bottom';
        $topRight = 'ull_orgchart_spacer_bottom';
        $bottomLeft = 'ull_orgchart_spacer_right';
        $bottomRight = 'ull_orgchart_spacer_empty';
    }
    $return = '';
    $return .= '<div class="ull_orgchart_spacer">';
    
    $return .= '<div class="ull_orgchart_spacer_quarter ' . $topLeft . '"></div>';
    $return .= '<div class="ull_orgchart_spacer_quarter ' . $topRight . ' inline"></div>';
    $return .= '<div class="ull_orgchart_spacer_quarter ' . $bottomLeft . '"></div>';
    $return .= '<div class="ull_orgchart_spacer_quarter ' . $bottomRight . ' inline"></div>';
    
    $return .= '</div>'; 
    
    return $return;
  }  
  
  
  
  
}