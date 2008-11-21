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
    
    // add meta data fields only for edit action
    if ($this->requestAction == 'edit')
    {
      $this->getWidgetSchema()->offsetSet('ull_flow_action_id', new sfWidgetFormInputHidden);
      $this->getWidgetSchema()->offsetSet('memory_comment', new sfWidgetFormInput(array(), array('size' => 50)));
      
      $this->getValidatorSchema()->offsetSet('ull_flow_action_id', new sfValidatorInteger(array('required' => false)));
      $this->getValidatorSchema()->offsetSet('memory_comment', new sfValidatorString(array('required' => false)));
    }    
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
//    var_dump($values);die;
    
    // move memory_comment to object (fromArray() only works for original model fields)
    if (isset($values['memory_comment'])) 
    {
      $this->object->memory_comment = $values['memory_comment'];
    }
    
    // move virtual columns to object
    $virtualColumns = $this->object->getVirtualColumnsAsArray();
    
    foreach ($virtualColumns as $column)
    {
      if (isset($values[$column])) {
        $this->object->$column = $values[$column];
      }
    }
    
//    var_dump($this->object);die
    
    return $this->object;
  }
  
}
