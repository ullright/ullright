<?php
/**
 * dynamic form for ullFlow
 *
 */
class ullFlowForm extends ullGeneratorForm
{
  /**
   * Configures the form
   *
   */
  public function configure()
  {
    parent::configure();
    
    $this->getWidgetSchema()->offsetSet('ull_flow_action_id', new sfWidgetFormInputHidden);
    $this->getValidatorSchema()->offsetSet('ull_flow_action_id', new sfValidatorInteger(array('required' => false)));
//    $this->getValidatorSchema()->offsetSet('ull_flow_action_id', new sfValidatorPass(array('required' => false)));
    
//    $this->setWidgets(array(
//      'ull_flow_action_id'  => new sfWidgetFormInputHidden,
//    ));
//    
//    $this->setValidators(array(
//        'ull_flow_action_id' => new sfValidatorInteger()
//    ));
  }
  
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
    return $this->object;
  }
  
}
