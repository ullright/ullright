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
abstract class ullDoctrineMapper extends ullMapper
{
  protected
    $modelName,
    $numberImported = 0
  ;
  
  /**
   * Constructor 
   *
   * @param array $data     The input data
   * @param array $mapping  The mapping with format "input key" => "output key"
   */
  public function __construct(array $data, array $mapping, $modelName)
  {
    $this->data = $data;
    $this->mapping = $mapping;
    $this->modelName = $modelName;
  }  
  
 
  
  
}