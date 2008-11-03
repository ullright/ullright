<?php
/**
 * dynamic form for ullFlow
 *
 */
class ullFlowForm extends ullGeneratorForm
{
//  /**
//   * Configures the form
//   *
//   */
//  public function configure()
//  {
//    $this->setWidgets(array(
//      'my_email'  => new sfWidgetFormInput,
//    ));
//    
//    $this->setValidators(array(
//        'my_email' => new sfValidatorString()
//    ));
//  }
//  
//  /**
//   * Configure the name of the model
//   *
//   * @return string
//   */
//  public function getModelName()
//  {
//    return 'UllFlowDoc';
//  }

  /**
   * Query also the virtual columns for the default values
   *
   * @see sfDoctrineForm
   */
  protected function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();
    
    $defaults = $this->getDefaults();
    
    $virtualColumns = $this->object->getVirtualValuesAsArray();
    
    $this->setDefaults($defaults + $virtualColumns);
  }
  
  /**
   * Update also the virtual Columns
   *
   * @see sfDoctrineForm
   * @return Doctrine_Record
   */
  public function updateObject()
  {
    parent::updateObject();
    
    $values = $this->getValues();
    
    $virtualColumns = $this->object->getVirtualColumnsAsArray();
    
    foreach ($virtualColumns as $column)
    {
      if (isset($values[$column])) {
        $this->object->$column = $values[$column];
      }
    }
    
    $this->object->assigned_to_ull_entity_id = 1;
    
    return $this->object;
  }
  
}
