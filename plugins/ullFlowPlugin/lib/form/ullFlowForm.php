<?php
/**
 * dynamic form for ullFlow
 *
 */
class ullFlowForm extends ullGeneratorForm
{
  protected
    $oldObject
  ;
  
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
    parent::updateObject();

    $this->setVirtualValues();
    $this->setAction();
    $this->setNext();
    $this->setMemory();
    
    $this->object->setTags($this->getValue('column_tags'));
    $this->object->duplicate_tags_for_search = $this->getValue('column_tags');
    $this->object->priority = $this->getValue('column_priority');
    
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
      if (isset($values[$column])) 
      {
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
    $actionSlug = sfContext::getInstance()->getRequest()->getParameter('action_slug', 'save_close');    
    
    if (!$action = Doctrine::getTable('UllFlowAction')->findOneBySlug($actionSlug))
    {
      throw new InvalidArgumentException('Invalid UllFlowAction given: ' . $actionSlug); 
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
    if (!$this->object->UllFlowAction->is_status_only)
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
