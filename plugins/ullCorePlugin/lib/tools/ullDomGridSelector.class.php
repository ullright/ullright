<?php

/**
 *  
 * @author Klemens Ullmann-Marx
 * @todo: allow giving row/column aliases as simple array ('id','name', ..)
 *
 */
class ullDomGridSelector
{
  protected
    $baseSelector,
    $rowSelector,
    $columnSelector,
    $rowAliases,
    $columnAliases,
    $headerBaseSelector,
    $headerColumnSelector
  ;
  
  public function __construct(
    $baseSelector,
    $rowSelector,
    $columnSelector = null,
    $rowAliases = array(),
    $columnAliases = array(),
    $headerBaseSelector = null, 
    $headerColumnSelector = null   
   
  ) 
  {
    $this->baseSelector = $baseSelector;
    $this->rowSelector = $rowSelector;
    $this->columnSelector = $columnSelector;
    $this->rowAliases = self::convertArray($rowAliases);
    $this->columnAliases = self::convertArray($columnAliases);
    $this->headerBaseSelector = $headerBaseSelector;
    $this->headerColumnSelector = $headerColumnSelector;
  }
  
  public function getBaseSelector()
  {
    return $this->baseSelector;
  }
  
  public function getRowSelector()
  {
    return $this->rowSelector;
  }
  
  public function getColumnSelector()
  {
    return $this->columnSelector;
  }
  
  public function getHeaderBaseSelector()
  {
    return $this->headerBaseSelector;
  }  
  
  public function getHeaderColumnSelector()
  {
    return $this->headerColumnSelector;
  }

  public function getFullRowSelector()
  {
    return $this->baseSelector . ' > ' . $this->rowSelector;
  }

  public function getFullHeaderColumnSelector()
  {
    if ($this->getHeaderBaseSelector())
    {
      return $this->headerBaseSelector . ' > ' . $this->headerColumnSelector;
    }
  }    
  
  public function get($row, $column = null)
  {
    if (!is_numeric($row))
    {
      $row = $this->getRowAlias($row);
    }
    
    if ($column and !$this->getColumnSelector())
    {
      throw new InvalidArgumentException('No column can be given because no column selector was set upon construction');
    }
    if ($column && !is_numeric($column))
    {
      $column = $this->getColumnAlias($column);
    }    
    
    $return = $this->baseSelector;
    
    $array = array();
    for($i = 1; $i <= $row; $i++)
    {
      $array[] = $this->rowSelector;
    }
    $return .= ' > ' . implode(' + ', $array);
    
    if ($this->getColumnSelector())
    {
      $array = array();
      for($i = 1; $i <= $column; $i++)
      {
        $array[] = $this->columnSelector;
      }
      $return .= ' > ' . implode(' + ', $array);
    }    
      
    return $return;
  }
  
  public function getHeader($column)
  {
    if (!is_numeric($column))
    {
      $column = $this->getColumnAlias($column);
    }    
    
    $return = $this->headerBaseSelector;
    
    $array = array();
    for($i = 1; $i <= $column; $i++)
    {
      $array[] = $this->headerColumnSelector;
    }
    $return .= ' > ' . implode(' + ', $array);
      
    return $return;
  }  
    
  protected function getRowAlias($alias)
  {
    if (!isset($this->rowAliases[$alias]))
    {
      throw new InvalidArgumentException('Unknown row alias ' . $alias);
    }
    
    return $this->rowAliases[$alias];
  }
  
  protected function getColumnAlias($alias)
  {
    if (!isset($this->columnAliases[$alias]))
    {
      throw new InvalidArgumentException('Unknown column alias ' . $alias);
    }
    
    return $this->columnAliases[$alias];
  }

  /**
   * Converts a simple array (eg. 'apple', 'banana') into a array of the format
   * 'apple' => 1, 'banana' => 2
   * @param $array
   * @return array
   */
  public static function convertArray($array)
  {
    if (is_numeric(key($array)))
    {
      $return = array();
      $i = 1;
      foreach ($array as $value)
      {
        $return[$value] = $i;
        $i++; 
      }
      
      return $return;
    }
    
   return $array;
  }
}
