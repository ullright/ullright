<?php

/**
 * ullDomGridSelector
 * 
 * This is a service class for functional tests. The aim is to make it easier
 * to test list views with a html tables ("grid")
 * 
 * Once a ullDomGridSelector is construced with the necessary configuration, 
 * arbitrary table cells can be accessed via row/column numbers or even better 
 * an human readable row or column alias
 * 
 * The methods return css ("DOM") selectors for the desired part of the grid
 * 
 * See ullDomGridSelectorTest.class.php for examples
 * 
 * The class also works for unordered lists and similar semantic structures
 * 
 * 
 * @author Klemens Ullmann-Marx
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
  
  /**
   * Constructor
   * 
   * @param string $baseSelector          The css selector for the whole table.
   *  Example: "table#my_list > tbody"
   *  
   * @param string $rowSelector           Row selector - usually "tr"
   * @param string $columnSelector        Column selector - usually "td"
   * @param array $rowAliases             Array of row aliases
   *  Example for a table of a user edit form:
   *  array(
   *    'first_name',
   *    'last_name',
   *    'password',
   *    ...
   *  )
   *                               
   * @param array $columnAliases          Array of column aliases
   *  Example for a list table of a user
   *  array(
   *    'id',
   *    'first_name',
   *    'last_name',
   *    'password',
   *    ...
   *    'created_at',
   *    ...
   *  )
   *   
   * @param string $headerBaseSelector    Selector for the table header
   *  Example: "table#my_table > thead"
   *  
   * @param string $headerColumnSelector  Selector for a header column - usually "th"
   * 
   * @return none
   */
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
  
  /**
   * Get a cell css selector by given row and column alias or number
   * 
   * @param mixed $row      Integer row number or row alias name
   * @param mixed $column   Integer column number or row alias name
   * @return string         Css selector
   */
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
  
    $return .= ' > ' . $this->rowSelector . ':nth-child('. $row .')';
    
    if ($this->getColumnSelector())
    {
      $return .= ' > ' . $this->columnSelector . ':nth-child('. $column .')';
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
    $return .= ' > ' . $this->headerColumnSelector . ':nth-child('. $column .')';
          
    return $return;
  }  
    
  public function getRowAlias($alias)
  {
    if (!isset($this->rowAliases[$alias]))
    {
      throw new InvalidArgumentException('Unknown row alias ' . $alias);
    }
    
    return $this->rowAliases[$alias];
  }
  
  public function getColumnAlias($alias)
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
