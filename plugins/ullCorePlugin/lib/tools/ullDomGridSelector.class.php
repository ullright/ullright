<?php

class ullDomGridSelector
{
  protected
    $baseSelector,
    $rowSelector,
    $columnSelector,
    $rowAliases,
    $columnAliases
  ;
  
  public function __construct(
    $baseSelector,
    $rowSelector,
    $columnSelector = null,
    $rowAliases = array(),
    $columnAliases = array()
  ) 
  {
    $this->baseSelector = $baseSelector;
    $this->rowSelector = $rowSelector;
    $this->columnSelector = $columnSelector;
    $this->rowAliases = $rowAliases;
    $this->columnAliases = $columnAliases;
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
}
