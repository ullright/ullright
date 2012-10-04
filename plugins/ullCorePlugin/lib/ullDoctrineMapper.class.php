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
    $originalMappedData,
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
    $this->originalMappedData = $rows;

    foreach ($rows as $rowNumber => $row)
    {
      $row = $this->modifyRowPreValidation($row);
      
      $this->validateAndSave($row, $rowNumber);
    }   

    return $rows;
  }
  
  
  /**
   * Foreach row built an model-specifig sfFormDoctrine, bind and save it 
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
      
      $object->free(true);
      unset($object);
      unset($generator);
    }
    else 
    {
      $this->generatorErrors[$rowIdentifier] = $generator;
    }    
    
    sfContext::getInstance()->getLogger()->info('ullDoctrineMapper::validateAndSave() numberImported: ' . $this->numberImported);
    sfContext::getInstance()->getLogger()->info('ullDoctrineMapper::validateAndSave() generatorError: ' . count($this->generatorErrors));
  }
  
  /**
   * Return the original mapped data before beeing modified by 
   * modifyRowPreValidation()
   */
  public function getOriginalMappedData()
  {
    return $this->originalMappedData;
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
  
  /**
   * Get generator errors as array
   * 
   * Example return:
   * 
   * array
   *   0 => 
   *     array
   *       'row_number' => 10
   *       'row_data' => 
   *         array
   *           'first_name' => string 'Andrea'
   *           'last_name' => string 'Hüber'
   *           'email' => string 'hüber@example.com'
   *           'UllNewsletterMailingLists' => string 'Product news'
   *       'global_errors => optional
   *       'field_errors' => 
   *         array
   *           'label' => string 'E-Mail'
   *           'error' => string 'Invalid.'
   *           'value' => string 'hüber@example.com'
   * 
   * @return array
   */
  public function getGeneratorErrorsArray()
  {
    $generators = $this->getGeneratorErrors();
    $originalData = $this->getOriginalMappedData();
    
    $errors = array();
    
    foreach ($generators as $rowNumber => $generator)
    {
      $rowErrors = array();
      
      $form = $generator->getForm();
      
      $rowErrors['row_number'] = $rowNumber + 1;
      $rowErrors['row_data'] = $originalData[$rowNumber];
      
      if ($form->hasGlobalErrors())
      {
        $rowErrors['global_errors'] = $form->renderGlobalErrors();
      }
      
      foreach ($form->getErrorSchema()->getErrors() as $fieldName => $error)
      {
        if ($form->offsetExists($fieldName)) 
        {
          $field = $form->offsetGet($fieldName);
          
          $fieldError = array();
          
          $fieldError['label'] = str_replace(' *', '', $field->renderLabelName());
          $fieldError['error'] = ullCoreTools::print_r_ordinary(
              trim(strip_tags(str_replace("\n", ' ', $field->renderError())))
          );
          
          if ($value = $error->getValue())
          {
            $fieldError['value'] = ullCoreTools::print_r_ordinary($value);
          }
          
          $rowErrors['field_error'] = $fieldError;
        }
      }
      
      $errors[] = $rowErrors;
    }
    
//     var_dump($errors);
    
    return $errors;
  }
  
  
  
}