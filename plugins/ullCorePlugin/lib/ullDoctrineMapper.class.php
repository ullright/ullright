<?php

/**
 * Map the keys of a source array to a target array with keys according to the 
 * given mapping.
 * 
 * Discards unmapped fields in the source data.
 * 
 * @see unit test ullDoctrineMapperTest.php for examples 
 * @author klemens.ullmann-marx@ull.at
 */
abstract class ullDoctrineMapper extends ullMapper
{
  protected
    $numberImported = 0,
    $generatorErrors = array()
  ; 
  
  /**
   * Constructor 
   *
   * @param array $data     The input data
   * @param array $mapping  The mapping with format "input key" => "output key"
   */
  public function __construct(array $data)
  {
    $this->data = $data;
    
    $this->configureMapping();
  }  

  
  /**
   * METHODS TO BE CUSTOMIZED
   */
  
  
  /**
   * Configure your mapping here and set it in $this->mapping
   * 
   * Format: "source name" => "target name"
   */
  abstract public function configureMapping();
  
  
  /**
   * Return a generater for your model here 
   * Can also be used to make custom tweaks to the columnsConfig etc.
   * 
   * @return ullGenerator
   */
  abstract public function getGenerator();


  /**
   * Find or create the doctrine record 
   * 
   * @param array $row
   * 
   * @return Doctrine_Record
   */
  abstract function getObject(array $row);  
  
  
  /**
   * Modify the row data before validation
   * 
   * Overwrite this method with custom logic if necessary
   
   * @param array $row
   * 
   * @return array 
   */
  public function modifyRowPreValidation(array $row)
  {
    return $row;
  }  
  
  
  /**
   * OTHER METHODS
   */  
  
  
  /**
   * Perform mapping, validation and save the objects if successful.
   * Otherwise the generators (including the sfForms) of rows with errors
   * can be obtained with getGeneratorErrors()
   *
   * @return array    The rows as returned by map()
   */
  public function mapValidateAndSave()
  {
    $rows = $this->map();

    foreach ($rows as $rowNumber => $row)
    {
      $row = $this->modifyRowPreValidation($row);
      
      $this->validateAndSave($row, $rowNumber);
    }   

    return $rows;
  }
  
  
  /**
   * Built an model-specifig sfFormDoctrine, bind and save it 
   *
   * @param array $row
   * @param integer $rowIdentifier  optional
   */
  public function validateAndSave(array $row, $rowIdentifier = null)
  {
    $generator = $this->getGenerator();
    
    $object = $this->getObject($row);
    
    $generator->buildForm($object);
  
    if ($generator->getForm()->bindAndSave($row))
    {
      $this->numberImported++;
    }
    else 
    {
      $this->generatorErrors[$rowIdentifier] = $generator;
    }    
  }
  
  
  /**
   * Get the number of imported rows
   *
   * @return integer
   */
  public function getNumberImported()
  {
    return $this->numberImported;
  }  
  
  
  /**
   * Return the generators (including the sfForm) of rows how could not be
   * imported due to validation errors
   * 
   * @return array
   */
  public function getGeneratorErrors()
  {
    return $this->generatorErrors;
  }
  
}