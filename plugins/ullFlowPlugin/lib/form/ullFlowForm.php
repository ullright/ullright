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
  }
  

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
        
    if (!$this->getDefault('effort_date'))
    {
      $this->setDefault('effort_date', date('Y-m-d')); 
    }        
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
    
    $this->setSubject();    
    $this->setAction();
    $this->setNext();
    $this->setMemory();
    
    return $this->object;
  }
  
  
  /**
   * Make sure that the native UllFlowDoc subject column is a string
   * 
   * We achive this by rendering the read-mode widget of virtual subject column 
   */
  protected function setSubject()
  {
    sfContext::getInstance()->getConfiguration()->loadHelpers('Escaping');
    
    $slug = UllFlowColumnConfigTable::findSubjectColumnSlug($this->object->UllFlowApp->id);
    $cc = UllFlowColumnConfigTable::findByAppIdAndSlug($this->object->UllFlowApp->id, $slug);
    $columnType = $cc->UllColumnType->class;
    
    $ccMock = new ullColumnConfiguration();
    $ccMock->setAccess('r');
    
    $formMock = new sfForm();
    
    $metaWidgetMock = new $columnType($ccMock, $formMock);
    $metaWidgetMock->addToFormAs('subject');
    
    $formMock->setDefault('subject', $this->object['subject']);

    // TODO: check why this is htmlentity escaped!
    $subjectAsString = ullCoreTools::esc_decode(strip_tags($formMock['subject']->render()));
    
    $this->object['subject'] = $subjectAsString;
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
      // Step One: get information about "next" from rule script
      // This is optional. The rule script can, but is not obligated to
      // set the next entity or step.
      $className = 'ullFlowRule' . sfInflector::camelize($this->object->UllFlowApp->slug);
      $rule = new $className($this->object);
      $next = $rule->getNext();
      
      // Step two: if the rule script did not supply an entity or a step
      // we use the default behaviour of the ullFlow action (e.g. "reopen")
      if (!isset($next['entity']) || !isset($next['step']))
      {
        $className = 'ullFlowActionHandler' . sfInflector::camelize(sfContext::getInstance()->getRequest()->getParameter('action_slug'));
        $handler = new $className($this);
        $next = array_merge($handler->getNext(), $next);
      }
      
      // Now update the object only for next parts which have been modified,
      // otherwise leave them as they were
      if (isset($next['entity'])) 
      {
        $this->object->UllEntity = $next['entity'];
      }
      
      if (isset($next['step']))
      {
        $this->object->UllFlowStep = $next['step'];
      }
      
//      var_dump(ullCoreTools::debugArrayWithDoctrineRecords($next));
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
