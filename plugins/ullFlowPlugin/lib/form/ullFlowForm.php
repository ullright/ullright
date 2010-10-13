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
      
      $this->getWidgetSchema()->offsetSet('duration_seconds', new ullWidgetTimeDurationWrite(array('fragmentation' => 5)));
      $this->getValidatorSchema()->offsetSet('duration_seconds', new ullValidatorTimeDuration(array('required' => false)));
      
      $this->getWidgetSchema()->offsetSet('effort_date', new ullWidgetDateWrite(array(), array('size' => 10)));
      $this->getValidatorSchema()->offsetSet('effort_date', new sfValidatorDate(array('required' => false)));
    }    
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

    $subjectAsString = strip_tags($formMock['subject']->render());

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
      // Step One: try to get information about "next" from ullFlowActionHandler
      $className = 'ullFlowActionHandler' . sfInflector::camelize(sfContext::getInstance()->getRequest()->getParameter('action_slug'));
      $handler = new $className($this);
      $next = $handler->getNext();
      
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
        $next['entity']->comment = 'popo';
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
