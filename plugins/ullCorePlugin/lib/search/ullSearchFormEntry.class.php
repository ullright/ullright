<?php

/**
 * Represents a single searchable field in a search form.
 */
class ullSearchFormEntry
{
  public $relations = array();
  public $columnName;
  public $isVirtual; //for ullFlowDoc, ullVentory, ...

  public $multipleCount = 1;
  public $uuid;

  /**
   * Creates a new search form entry,
   * optionally parsing it's members from a given string.
   * 
   * @param $fromString a string describing a search form entry.
   * @return a new search form entry
   */
  public function __construct($fromString = null)
  {
    $this->isVirtual = false;

    if ($fromString != null)
    {
      if (strpos($fromString, 'isVirtual.') === 0)
      {
        $this->isVirtual = true;
        $fromString = substr($fromString, 10);
      }

      $lastDotPosition = strrpos($fromString, '.');
      if ($lastDotPosition === false)
      {
        $this->columnName = $fromString;
        //throw new RuntimeException('Invalid SearchFormEntry string.');
      }
      else 
      {
        $this->columnName = substr($fromString, $lastDotPosition + 1);
        $tempString = substr($fromString, 0, $lastDotPosition);
        $this->relations = explode('.', $tempString);
      }
    }

    //$this->uuid = str_replace('.', '', uniqid('', true));
  }

  /**
   * Tests this search form entry for equality.
   * The contained UUID is not tested.
   * 
   * @param $searchFormEntry another search form entry
   * @return boolean true or false
   */
  public function equals(ullSearchFormEntry $searchFormEntry)
  {
    if ($this->columnName != $searchFormEntry->columnName)
    {
      return false;
    }

    if ($this->relations !== $searchFormEntry->relations)
    {
      return false;
    }

    if ($this->isVirtual !== $searchFormEntry->isVirtual)
    {
      return false;
    }

    return true;
  }

  /**
   * Renders a string representation of this search form entry.
   * 
   * @return a string describing this search form entry - can be used
   * with __construct().
   */
  public function __toString()
  {
    $relationString = ($this->isVirtual) ? 'isVirtual.' : '';
    $relationString .= implode('.', $this->relations);
    $relationString .= ($relationString == '' || $relationString == 'isVirtual.') ? '' : '.';
    return $relationString . $this->columnName; // . '.' . ($this->isVirtual ? '1' : '0');
  }
}
