<?php

/**
 * Represents a tree of objects composed of nodes
 * 
 * Each node has a data payload $data,
 * customizeable meta data $meta,
 * and possibly child nodes $subnodes
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
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
   * @param mixed $data Payload of the node
   * @return none
   */
  public function __construct($data)
  {
    $this->data = $data;
    
    $this->setLevel(1);
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
   * Normally a given node is attached as a normal leaf/subnode
   * 
   * Using the optional $meta parameter the node is stored in the meta data
   * storage instead under the given key
   * 
   * @param ullTreeNode $node
   * @param string $meta        Don't add as subnode, but under the given meta key
   * @return self
   */
  public function addSubnode(ullTreeNode $node, $meta = null)
  {
    $node->fixLevels($this->getLevel() + 1);
    
    if ($meta)
    {
      $storage = &$this->meta[$meta];
    }
    else
    {
      $storage = &$this->subnodes;
    }
    
    if (count($storage) == 0)
    {
      $node->setIsFirst(true);
    }
    
    $node->setIsLast(true);
    
    if (count($storage) >=1)
    {
      end($storage)->setIsLast(false);
    }
    
    $storage[] = $node;
    
    return $this;
  }
  
  
  /**
   * Get the subnodes
   * 
   * Normally returns the existing subnodes/leafes
   * 
   * Using the optional $meta parameter the node stored in the meta data
   * storage under the given key is returned 
   * 
   * @param string $meta        Use the given meta key 
   * @return array
   */
  public function getSubnodes($meta = null)
  {
    if ($meta)
    {
      $storage = &$this->meta[$meta];
    }
    else
    {
      $storage = &$this->subnodes;
    }    
    
    return $storage;
  }
  
  
  /**
   * Return the first subnode
   * 
   * @return ullTreeNode
   */
  public function getFirstSubnode()
  {
    $subs = $this->subnodes;
    
    return reset($subs);
  }

  
  /**
   * Check if the node has subnodes
   * 
   * Normally checks the existing subnodes/leafes
   * 
   * Using the optional $meta parameter it looks for nodes stored in the meta data
   * storage under the given key  
   * 
   * @param string $meta        Use the given meta key
   * @return boolean
   */
  public function hasSubnodes($meta = null)
  {
    if ($meta)
    {
      $storage = &$this->meta[$meta];
    }
    else
    {
      $storage = &$this->subnodes;
    }
    
    return (count($storage)) ? true : false;
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
   * Set if the current node is the first one of a level
   * 
   * @param boolean $boolean
   * @return $this
   */
  public function setIsFirst($boolean)
  {
    $this->setMeta('is_first', (boolean) $boolean);
    
    return $this;
  }
  
  
  /**
   * Check if the current node is the first one of a level
   * @return unknown_type
   */
  public function isFirst()
  {
    if ($this->hasMeta('is_first'))
    {
      return $this->getMeta('is_first');
    }
    
    return false;
  }
  
  
  /**
   * Set if the current node is the last one of a level
   * 
   * @param boolean $boolean
   * @return $this
   */
  public function setIsLast($boolean)
  {
    $this->setMeta('is_last', (boolean) $boolean);
    
    return $this;
  }
  
  
  /**
   * Check if the current node is the last one of a level
   * @return unknown_type
   */
  public function isLast()
  {
    if ($this->hasMeta('is_last'))
    {
      return $this->getMeta('is_last');
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

    $array['data'] = (string) $this->data;
    $array['meta'] = array();
    // arrayize ullTreeNodes in the meta array
    foreach ($this->meta as $key => $singleMeta)
    {
      if (is_array($singleMeta) && reset($singleMeta) instanceof ullTreeNode)
      {
        foreach($singleMeta as $nodeKey => $nodeValue)
        {
          $array['meta'][$key][$nodeKey] = $nodeValue->toArray();
        }
      }  
      else
      {
        $array['meta'][$key] = $singleMeta;
      }
    }
    $array['subnodes'] = array();
    
    foreach ($this->subnodes as $subnode)
    {
      $array['subnodes'][] = $subnode->toArray();
    }
    
    return $array;
  }

  
  /**
   * Set the current level of recursion
   * 
   * @param $level
   * @return self
   */
  public function setLevel($level)
  {
    $this->setMeta('level', $level);
    
    return $this;
  }
  
  
  /**
   * Returns the current level of recursion
   * 
   * @return integer
   */
  public function getLevel()
  {
    return $this->getMeta('level');
  }
  
  
  /**
   * Recusivly fix the level of added items
   * 
   * @param integer $level
   * @return none
   */
  public function fixLevels($level)
  {
    $this->setLevel($level);

    if ($this->hasSubnodes())
    {
      foreach ($this->getSubnodes() as $subnode)
      {
        $subnode->fixLevels($level + 1);   
      }
    }
  }
  
}