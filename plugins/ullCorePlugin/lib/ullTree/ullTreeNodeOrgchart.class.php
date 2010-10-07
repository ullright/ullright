<?php

/**
 * Custom ullTreeNode for ullOrgchart
 * 
 * Adds support for subordinates and assistants
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class ullTreeNodeOrgchart extends ullTreeNode
{

  /**
   * Add a subordinate
   * 
   * @param ullTreeNode $node
   * @return self
   */
  public function addSubordinate(ullTreeNode $node)
  {
    $this->addSubnode($node, 'subordinates');
    
    return $this;
  }
  
  /**
   * Get subordinates
   * 
   * @return array
   */
  public function getSubordinates()
  {
    return $this->getSubnodes('subordinates');
  } 
  
  
  /** 
   * Check if we have subordinates
   * 
   * @return boolean
   */
  public function hasSubordinates()
  {
    return $this->hasSubnodes('subordinates');
  }
  
}