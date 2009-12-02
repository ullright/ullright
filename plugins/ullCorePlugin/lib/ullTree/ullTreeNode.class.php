<?php

class ullTreeNode
{
  protected 
    $data,
    $meta = array(),
    $subnodes = array()
  ;
  
  /**
   * Constructor
   * 
   * @param mixed $data
   * @return none
   */
  public function __construct($data)
  {
    $this->data = $data;  
  }
  
  
  /**
   * Get data payload
   * 
   * @return mixed
   */
  public function getData()
  {
    return $this->data;
  }
  
  
  /**
   * Add a subnode
   * 
   * @param ullTreeNode $node
   * @return self
   */
  public function addSubnode(ullTreeNode $node)
  {
    if (count($this->subnodes) == 1)
    {
      reset($this->subnodes)->setIsLeftMost(true);
    }
    
    if (count($this->subnodes) >=1)
    {
      $node->setIsRightMost(true);
      end($this->subnodes)->setIsRightMost(false);
    }
    
    $this->subnodes[] = $node;
    
    return $this;
  }
  
  
  /**
   * Get the subnodes
   * 
   * @return array
   */
  public function getSubnodes()
  {
    return $this->subnodes;
  }

  
  /**
   * Check if the node has subnodes
   * 
   * @return boolean
   */
  public function hasSubnodes()
  {
    return (count($this->subnodes)) ? true : false;
  }
  
  /**
   * Set meta data
   * 
   * @param string $key
   * @param mixed $value
   * @return self
   */
  public function setMeta($key, $value)
  {
    if ($value === false)
    {
      unset($this->meta[$key]);
    }
    else
    {
      $this->meta[$key] = $value;
    }
    
    return $this;
  }
  

  /** 
   * Check if meta data exists for a given key
   * 
   * @param string $key
   * @return boolean
   */
  public function hasMeta($key)
  {
    return (isset($this->meta[$key])) ? true : false;
  }
  
  
  /**
   * Get meta data
   * 
   * @param string $key
   * @throws InvalidArgumentException
   * @return mixed
   */
  public function getMeta($key)
  {
    if (!isset($this->meta[$key]))
    {
      throw new InvalidArgumentException('Invalid key given: ' . $key);
    }

    return $this->meta[$key];
  }
  
  
  /**
   * Set if the current node is the leftmost one of a level
   * 
   * @param boolean $boolean
   * @return $this
   */
  public function setIsLeftMost($boolean)
  {
    $this->setMeta('leftmost', (boolean) $boolean);
    
    return $this;
  }
  
  
  /**
   * Check if the current node is the leftmost one of a level
   * @return unknown_type
   */
  public function isLeftMost()
  {
    if ($this->hasMeta('leftmost'))
    {
      return $this->getMeta('leftmost');
    }
    
    return false;
  }
  
  
  /**
   * Set if the current node is the leftmost one of a level
   * 
   * @param boolean $boolean
   * @return $this
   */
  public function setIsRightMost($boolean)
  {
    $this->setMeta('rightmost', (boolean) $boolean);
    
    return $this;
  }
  
  
  /**
   * Check if the current node is the rightmost one of a level
   * @return unknown_type
   */
  public function isRightMost()
  {
    if ($this->hasMeta('rightmost'))
    {
      return $this->getMeta('rightmost');
    }
    
    return false;
  }  
  
  
  /**
   * Return array format of node and subnodes
   * 
   * @return array
   */
  public function toArray()
  {
    $array = array();

    $array['data'] = $this->data;
    $array['meta'] = $this->meta;
    $array['subnodes'] = array();
    
    foreach ($this->subnodes as $subnode)
    {
      $array['subnodes'][] = $subnode->toArray();
    }
    
    return $array;
  }
  
}