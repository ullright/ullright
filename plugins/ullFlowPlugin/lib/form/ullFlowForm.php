<?php
/**
 * dynamic form for ullFlow
 *
 */
class ullFlowForm extends ullGeneratorForm
{
  
  protected
    $ullFlowAction = null
  ;
  
  /**
   * Configures the form
   *
   */
  public function configure()
  {
    parent::configure();
    
    // add meta data fields only for create/edit action
    //TODO: why here? why not in generator?
    
    if ($this->requestAction == 'create' or $this->requestAction == 'edit')
    {
      $this->getWidgetSchema()->offsetSet('memory_comment', new sfWidgetFormInput(array(), array('size' => 50)));
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
    
    $this->setDefaults(array_merge($this->getDefaults(), 
        $this->object->getVirtualValuesAsArray()));
  }
  
  /**
   * Update also the virtual Columns
   *
   * @see sfDoctrineForm
   * @return Doctrine_Record
   */
  public function updateObject($values = null)
  {
    $this->removeActionHandlerFormFieldValues(); 
    
    parent::updateObject();
    
    $this->setAction();
    $this->setNext();
    $this->setMemory();

    return $this->object;
  }
  
  /**
   * Remove all form field values generated by ullFlow action handlers
   * from the values array - otherwise Doctrine complains later on
   * 
   * Additionally, remove ullFlowActionHandlerAssignToUser's fields
   * (because master admins are allowed to always assign)
   * 
   * @return none
   */
  protected function removeActionHandlerFormFieldValues()
  {
    $formFieldNamesToRemove = array();
    
    foreach($this->object->UllFlowStep->UllFlowStepActions as $stepAction)
    {
      $className = 'ullFlowActionHandler' . sfInflector::camelize($stepAction->UllFlowAction->slug);
      $formFieldNames = call_user_func(array($className, 'getFormFieldNames'));
      $formFieldNamesToRemove += $formFieldNames;
    }

    //master admin are always allowed to assign, so the fields need to
    //be removed anyway
    $formFieldNames = ullFlowActionHandlerAssignToUser::getFormFieldNames();
    $formFieldNamesToRemove += $formFieldNames;
    
    foreach($formFieldNamesToRemove as $name)
    {
      unset($this->values[$name]);
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
    $actionSlug = sfContext::getInstance()->getRequest()->getParameter('action_slug', 'save_close');    
    
    if ($this->ullFlowAction = Doctrine::getTable('UllFlowAction')->findOneBySlug($actionSlug))
    {
      // TODO: maybe this could be refactored into UllFlowDoc...
      
      // Don't update doc with status only actions (e.g. editing a closed doc should stay closed)
      if ($this->ullFlowAction->is_status_only)
      {
        $this->object->setMemoryAction($this->ullFlowAction);
      }
      else
      {
        $this->object->UllFlowAction = $this->ullFlowAction;
      }
    }
    else
    {
      throw new InvalidArgumentException('Invalid UllFlowAction given: ' . $actionSlug); 
    }
  } 
    
  /**
   * parses the app's rules and sets the next entity and step accordingly
   * 
   * (only if we don't have a status_only action)
   * 
   */
  protected function setNext()
  {
    if (!$this->ullFlowAction->is_status_only)
    {
      // Step One: get information about "next" from ullFlowActionHandler
      $className = 'ullFlowActionHandler' . sfInflector::camelize(sfContext::getInstance()->getRequest()->getParameter('action_slug'));
      $handler = new $className($this);
      $next = $handler->getNext();
      
//      var_dump($next); die;
      
      if (isset($next['entity'])) 
      {
        $this->object->UllEntity = $next['entity'];
      }
      
      if (isset($next['step']))
      {
        $this->object->UllFlowStep = $next['step'];
      }
      
      // Step Two: get information about "next" from rule script
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
   * adds the memory data to the object
   *
   */
  protected function setMemory()
  {
    $values = $this->getValues();
    
    if (isset($values['memory_comment'])) 
    {
      $this->object->memory_comment = $values['memory_comment'];
    }
  }    
  
}
