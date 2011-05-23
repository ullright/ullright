<?php

class ullTreeMenuSelectRenderer
{
  protected
    $node,
    $idColumnn,
    $result = array()
  ;
  
  /**
   * Returns a new ullTreeNavigationRenderer instance,
   * based on a starting ullTreeNode.
   * 
   * Renders nodes as an array $id => $indented_name for a select box
   * 
   * @param ullTreeNode $node the node which should be rendered
   */
  public function __construct(ullTreeNode $node, $idColumn = 'id')
  {
    $this->node = $node;
    $this->idColumn = $idColumn;
  }

  /**
   * @return array
   */
  public function render()
  {
    $this->doRendering($this->node);
    
    return $this->result;
  }
  
  /**
   * 
   * @param ullTreeNode $node
   */
  public function doRendering(ullTreeNode $node)
  {
    $i = $node->getLevel();
    $indent = '';
    
    while($i--) 
    {
      $indent .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
    }
    
    $idColumn = $this->idColumn;
    
    $this->result[$node->getData()->$idColumn] = $indent . $node->getData()->name;
    
    if ($node->hasSubnodes())
    {
      foreach ($node->getSubnodes() as $subNode)
      { 
        $this->doRendering($subNode);
      }        
    }
  }

}
