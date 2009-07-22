<?php 

class ullColumnConfigCollection implements ArrayAccess, Countable, IteratorAggregate
{
  protected 
    $columnsConfig = array(),
    $action = 'edit'
  ;
  
  /**
   * Constructor
   * 
   * @param $action string symfony controller action. one of 'list', 'create', 'edit'
   * @return none
   */
  public function __construct($action = 'edit')
  {
    $this->action = $action;
  }
  
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
   * Sets the symfony controller action
   * 
   * @param $action string
   * @return none
   */
  public function setAction($action)
  {
    $this->action = $action;
  }
  
  /**
   * Get the symfony controller action
   * 
   * @return string
   */
  public function getAction()
  {
    return $this->action;
  }
  
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

  /**
   * Creates a new ColumnConfiguration
   * 
   * @param $columnName
   * @return ullColumnConfiguration
   */
  public function create($columnName)
  {
    $this->columnsConfig[$columnName] = new UllColumnConfiguration;
    $this->columnsConfig[$columnName]->setColumnName($columnName);
    
    return $this->columnsConfig[$columnName];
  }
  
  /**
   * Disables the given columns
   * 
   * @param $array array of columnNames
   * @return none
   */
  public function disable(array $array)
  {
    foreach ($array as $columnName)
    {
      $this->columnsConfig[$columnName]->setAccess(false);     
    }
  }
  
  /**
   * Returns true if the current action is 'list'
   * 
   * @return boolean
   */
  public function isListAction()
  {
    if ($this->action == 'list')
    {
      return true;   
    }  
  }
  
  /**
   * Returns true if the current action is 'create'
   * 
   * @return boolean
   */  
  public function isCreateAction()
  {
    if ($this->action == 'create')
    {
      return true;   
    }  
  }  

  /**
   * Returns true if the current action is 'edit'
   * 
   * @return boolean
   */
  public function isEditAction()
  {
    if ($this->action == 'edit')
    {
      return true;   
    }  
  }  

  /**
   * Returns true if the current action is 'create' or 'edit'
   * 
   * @return boolean
   */  
  public function isCreateOrEditAction()
  {
    if ($this->isCreateAction() || $this->isEditAction())
    {
      return true;
    }  
  }
  
  
}