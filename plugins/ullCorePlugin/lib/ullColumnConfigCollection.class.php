<?php 

class ullColumnConfigCollection implements ArrayAccess, Countable, IteratorAggregate
{
  protected 
    $columnsConfig = array()
  ;
  
  // ArrayAccess methods
  
  public function offsetExists($offset)
  {
    if (array_key_exists($offset, $this->columnsConfig))
    {
      return true;
    }    
    
    return false;
  }
  
  public function offsetGet($offset)
  {
    if ($this->offsetExists($offset))
    {
      return $this->columnsConfig[$offset];
    }
    else
    {
      throw new InvalidArgumentException('Invalid offset given: ' . $offset);
    } 
  }
  
  public function offsetSet($offset, $value)
  {
    if ($value instanceof ullColumnConfiguration)
    {
      $this->columnsConfig[$offset] = $value;
    }
    else
    {
      throw new InvalidArgumentException('Given value must be a ullColumnConfiguration object');
    }
  }
  
  public function offsetUnset($offset)
  {
    if ($this->offsetExists($offset))
    {
      unset($this->columnsConfig[$offset]);
    }
    else
    {
      throw new InvalidArgumentException('Invalid offset given: ' . $offset);
    } 
  }
  
  public function getFirst()
  {
    return reset($this->columnsConfig);
  }
  
  public function getLast()
  {
    return end($this->columnsConfig);
  }  
  
  // Iterator methods
  
  public function getIterator()
  {
    return new ArrayIterator($this->columnsConfig);
  }  
  
  // Countable methods
  
  public function count()
  {
    return count($this->columnsConfig);
  }
  
  // Native methods
  
  /**
   * Returns the keys of the columnsConfig as array similar to array_keys()
   * 
   * @return array
   */
  public function getKeys()
  {
    return array_keys($this->columnsConfig);
  }
  
  public function order($array)
  {
    $this->columnsConfig = ullCoreTools::orderArrayByArray($this->columnsConfig, $array);
  }
  
  /**
   * Takes an array of keys and orders the bottom of the columnsConfig accordingly
   * See ullColumnConfigCollectionTest for example.
   * 
   * @param $array array of keys
   * @return none
   */
  public function orderBottom($array)
  {
    $bottom = array();
    foreach ($array as $key)
    {
      $bottom[$key] = $this->columnsConfig[$key];
      unset($this->columnsConfig[$key]);
    }

    $this->columnsConfig = array_merge($this->columnsConfig, $bottom);      
  }
  
}