<?php

/**
 * Map the keys of a source array to a target array with keys according to the 
 * given mapping.
 * 
 * Discards unmapped fields in the source data.
 * 
 * @see unit test ullMapperTest.php for examples 
 * @author klemens.ullmann-marx@ull.at
 */
class ullMapper
{
  protected
    $data = array(),
    $mapping = array(),
    $errors = array()
  ;
  
 
  /**
   * Constructor 
   *
   * @param array $data     The input data
   * @param array $mapping  The mapping with format "input key" => "output key"
   */
  public function __construct(array $data, array $mapping)
  {
    $this->data = $data;
    $this->mapping = $mapping;
  }
  
  /**
   * Check if the mapping produced errors
   * 
   * @return: boolean
   */
  public function hasErrors()
  {
    return (boolean) count($this->errors);
  }
  
  /**
   * Get an array of errors
   * 
   * @return: array
   */
  public function getErrors()
  {
    return $this->errors;
  }
  
  /**
   * Perform the mapping
   * 
   * @return: array
   */
  public function doMapping()
  {
    // Investigate if mapped columns are not in the supplied data at all
    $columnCheck = array();
    
    $result = array();
    
    foreach ($this->data as $key => $line)
    {
      $lineResult = array();
      
      foreach ($this->mapping as $sourceName => $targetName)
      {
        if (isset($line[$sourceName]))
        {
          $lineResult[$targetName] = $line[$sourceName];
          $columnCheck[$sourceName] = true;
        }
      } // end of foreach field
      
      // Make sure we have all target fields in the output array      
      $lineResult = array_merge(
        $this->getEmptyTargetLine(),
        $lineResult
      );
      
      $result[$key] = $lineResult;
    } // end of foreach rows
    
    // Investigate if mapped columns are not in the supplied data at all 
    // and populate the errors array if so
    foreach ($this->getMappingSourceFields() as $sourceName)
    {
      if (!isset($columnCheck[$sourceName]))
      {
        $this->errors[$sourceName] = __(
          'Warning: no column "%column%" supplied', 
          array('%column%' => $sourceName),
          'ullCoreMessages'
        );
      }
    }    
    
    return $result;
  }
  
  /**
   * Helper method to get only the source fields
   * (The left part of the mapping)
   * 
   * @return: array
   */
  public function getMappingSourceFields()
  {
    return array_keys($this->mapping);
  }
  
  
  /**
   * Create an empty target line array
   * 
   * Format: targetFieldName => null
   * 
   * @return: array
   */
  public function getEmptyTargetLine()
  {
    $result = array();
    
    foreach ($this->mapping as $targetFieldName)
    {
      $result[$targetFieldName] = null;
    }
    
    return $result;
  } 
  
  
}