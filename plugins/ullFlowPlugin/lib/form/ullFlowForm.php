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
//      $this->getWidgetSchema()->offsetSet('ull_flow_action_id', new sfWidgetFormInputHidden);
//      $this->getWidgetSchema()->offsetSet('assigned_to_ull_entity_id', new sfWidgetFormInputHidden);
//      $this->getWidgetSchema()->offsetSet('assigned_to_ull_flow_step_id', new sfWidgetFormInputHidden);
      $this->getWidgetSchema()->offsetSet('memory_comment', new sfWidgetFormInput(array(), array('size' => 50)));
      
//      $this->getValidatorSchema()->offsetSet('ull_flow_action_id', new sfValidatorInteger(array('required' => false)));
//      $this->getValidatorSchema()->offsetSet('assigned_to_ull_entity_id', new sfValidatorInteger(array('required' => false)));
//      $this->getValidatorSchema()->offsetSet('assigned_to_ull_flow_step_id', new sfValidatorInteger(array('required' => false)));
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

//    $values = $this->getValues();
//    var_dump($values);die;
    
    $this->setVirtualValues();
    $this->setAction();
    $this->setNext();
    $this->setMemoryComment();
    $this->sendMails();
    
//    var_dump($this->object->toArray());die;
    
    return $this->object;
  }
  
  /**
   * adds the values of the virtual columns to the object
   *
   */
  protected function setVirtualValues()
  {
    $values = $this->getValues();
    
    $virtualColumns = $this->object->getVirtualColumnsAsArray();
    
    foreach ($virtualColumns as $column)
    {
      if (isset($values[$column])) {
        $this->object->$column = $values[$column];
      }
    }
  }
  
  /**
   * parses the given ullFlow action from the submit_xxx request params
   * and injects the action id into the request params
   *
   * @throws InvalidArgumentException if no valid UllFlowAction->slug is given
   */
  protected function setAction()
  { 
    $request = sfContext::getInstance()->getRequest();
    $submitMode = ull_submit_tag_parse();
    
    // set default action
    $submitMode = $submitMode ? $submitMode : 'save_close';
    
    if (!$action = Doctrine::getTable('UllFlowAction')->findOneBySlug($submitMode))
    {
      throw new InvalidArgumentException('Invalid UllFlowAction given: ' . $submitMode); 
    }
    
    $this->object->UllFlowAction = $action;
  } 
    
  /**
   * parses the app's rules and sets the next entity and step accordingly
   * 
   * (only if we don't have a status_only action)
   * 
   */
  protected function setNext()
  {
    if (!$this->object->UllFlowAction->status_only)
    {
      $className = 'ullFlowRule' . sfInflector::camelize($this->object->UllFlowApp->slug);
      $rule = new $className($this->object);
      $next = $rule->getNext();
      
      if (isset($next['entity'])) 
      {
        $this->object->UllEntity = $next['entity'];
      }
      
      if (isset($next['step']))
      {
        $this->object->UllFlowStep = $next['step'];
      }
    }
  }

  /**
   * adds the memory comment to the object
   *
   */
  protected function setMemoryComment()
  {
    $values = $this->getValues();
    
    if (isset($values['memory_comment'])) 
    {
      $this->object->memory_comment = $values['memory_comment'];
    }
  }  
  
  protected function sendMails()
  {
    if ($this->object->UllFlowAction->notify_next) 
    {
      $mail = new ullFlowMailNotifyNext($this->object);
      $mail->send();
    }    
  }
  
}
