<?php

/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
abstract class PluginUllFlowDoc extends BaseUllFlowDoc
{

  protected
    $memoryComment = '',
    $memoryDuration = 0,
    $memoryAction = null,
    $createMemory = true
  ;
  
  // TODO: give UllFlowApp in the constructor to force check if 
  //  a virtual column exists?
  // UPDATE KU 2011-06-18: This would also be nice to load defaults, in particular
  // the start-step. But it seems difficult to overwrite the Doctrine_Record constructor  
  
  /**
   * String representation
   *
   * @return string
   */
  public function __toString()
  {
    return $this->UllFlowApp->doc_label . ' "'. $this->subject . '"';
  }
  
  /**
   * add Doctrine_Filter to handle virtual columns transparently
   *
   */
  public function setUp()
  {
    parent::setUp();
    
    $this->unshiftFilter(new UllFlowDocRecordFilter());
  }

  /**
   * Allow to disable UllMemory creation upon saving
   *
   * @param Doctrine_Connection $conn 
   * @param boolean $createMemory
   */
  public function save(Doctrine_Connection $conn = null, $createMemory = true)
  {
    $this->createMemory = (boolean) $createMemory;
    
    parent::save($conn);
  }
  
  /**
   * pre save hook
   * 
   * always makes the UllFlowDoc record dirty (=modified)
   * this is necessary, because often only the virtual columns change, but not
   * the native columns.
   *
   * @param unknown_type $event
   */
  public function preSave($event)
  {
    $this->dirty = $this->dirty == 1 ? 2 : 1;
  }
  
  /**
   * pre insert record hook
   * 
   * set defaults
   *
   * @param unknown_type $event
   */
  public function preInsert($event)
  {
    $this->loadDefaults();
    $this->createFirstMemory();
    
    if ($this->createMemory)
    {
      $this->createMemory();
    }      
  }
  
  /**
   * pre update record hook
   *
   * @param unknown_type $event
   */  
  public function preUpdate($event)
  {
    if ($this->createMemory)
    {
      $this->createMemory();
    }    
    
    $this->handleModifiedDueDate($event);
  }
  
  
  /**
   * Make sure that the native UllFlowDoc subject column is a string
   * It could also be a e.g. a foreign key id
   * 
   * We achive this by rendering the read-mode widget of virtual subject column 
   */
  public function setSubject($value)
  {
    $slug = UllFlowColumnConfigTable::findSubjectColumnSlug($this->ull_flow_app_id);
    
    // For test fixture loading (no columConfig yet)
    if (!$slug)
    {
      $this->_set('subject', $value);
      
      return;
    }
    
    $cc = UllFlowColumnConfigTable::findByAppIdAndSlug($this->ull_flow_app_id, $slug);
    $columnType = $cc->UllColumnType->class;
    
    $ccMock = new ullColumnConfiguration();
    $ccMock->setAccess('r');
    
    $formMock = new sfForm();
    
    $metaWidgetMock = new $columnType($ccMock, $formMock);
    $metaWidgetMock->addToFormAs('subject');
    
    $formMock->setDefault('subject', $value);

    // TODO: check why this is htmlentity escaped!
    sfContext::getInstance()->getConfiguration()->loadHelpers('Escaping');
    $subjectAsString = ullCoreTools::esc_decode(strip_tags($formMock['subject']->render()));
    
    $this->_set('subject', $subjectAsString);
  }  

  
  /**
   * Don't update doc with status only actions (e.g. editing a closed doc should stay closed)
   * 
   * @param UllFlowAction $value
   */
  public function setUllFlowActionWithStatusOnlyDetection(UllFlowAction $value)
  {
    if ($value->is_status_only)
    {
      $this->setMemoryAction($value);
    }
    else
    {
      $this->UllFlowAction = $value;
    }    
  }
  
  /**
   * Checks for a modified due date and resets mail notification fields.
   * @param Doctrine_Event $event
   */
  protected function handleModifiedDueDate($event)
  {
    //check if due date was modified
    $modifiedOldValues = $event->getInvoker()->getModified(true);
    
    if (isset($modifiedOldValues['due_date']))
    {
      $newDueDate = strtotime($event->getInvoker()->get('due_date'));
      
      //safety check (due_date virtual column could be 'date' instead of 'datetime')
      if (strtotime($modifiedOldValues['due_date']) == $newDueDate)
      {
        return;
      }      
      
      //check if due date was moved beyond current date
      //if true, reset mail notification fields
      if ($newDueDate > time())
      {
        $notifyFields = array(
          'owner_due_reminder_sent',
          'owner_due_expiration_sent', 
          'creator_due_expiration_sent'
        );
        
        foreach($notifyFields as $fieldName)
        {
          $event->getInvoker()->set($fieldName, false);
        }
      }
    }
  }
  
  /**
   * transparently set the UllFlowMemory comment
   *
   * @param string $value
   */
  public function setMemoryComment($value)
  {
    $this->memoryComment = $value;
  }
  
  /**
   * transparently get the current UllFlowMemory comment
   *
   * @param string $value
   */
  public function getMemoryComment()
  {
    return $this->memoryComment;
  }
  
  /**
   * Sets a custom UllFlowMemory action.
   * 
   * If none given (default) the doc's action is used.
   *
   * @param string $value
   */
  public function setMemoryAction($value)
  {
    $this->memoryAction = $value;
  }
  
  /**
   * Get the current custom UllFlowMemory action
   *
   * @param string $value
   */
  public function getMemoryAction()
  {
    return $this->memoryAction;
  }   
  
  /**
   * Set the value of a virtual column
   *
   * @param string $ullFlowColumnConfigSlug
   * @param string $value
   * @return mixed
   */
  public function setValueByColumn($ullFlowColumnConfigSlug, $value)
  {
    $ullFlowValue = UllFlowValueTable::findByDocIdAndSlug($this->id, $ullFlowColumnConfigSlug);
    $ullFlowValue->value = $value;
    
    return $ullFlowValue->save();
  }  
  
  /**
   * Get the value of a virtual column
   *
   * @param string $ullFlowColumnConfigSlug
   * @return mixed
   */
  public function getValueByColumn($ullFlowColumnConfigSlug)
  {
    $ullFlowValue = UllFlowValueTable::findByDocIdAndSlug($this->id, $ullFlowColumnConfigSlug);
    if ($ullFlowValue)
    {
      return $ullFlowValue->value;
    }
  }
  
  /**
   * Returns an array with the values of the virtual columns
   * 
   * Example: 
   * array(
   *   'my_subject' => 'This is my subject',
   *   'my_email' => 'darth.vader@ull.at',
   * );
   * 
   * @return array
   */
  public function getVirtualValuesAsArray()
  {
    $values = $this->UllFlowValues;
    
    $return = array();
    
    foreach ($values as $value)
    {
      $return[$value->UllFlowColumnConfig->slug] = $value->value;
    }
    
    return $return;
  }
  
  /**
   * Returns an array containing the virtual columns
   * 
   * Example: 
   * array(
   *   'my_subject',
   *   'my_email', 
   * );
   * 
   * @return array
   */
  public function getVirtualColumnsAsArray()
  {
    $columns = $this->UllFlowApp->UllFlowColumnConfigs;

    $return = array();
    
    foreach ($columns as $column)
    {
      $return[] = $column->slug;
    }
    
    return $return;
  }  
  
  
  /**
   * Get ullFlowMemories ordered by date
   * 
   * @return Doctrine_Collection
   */
  public function getUllFlowMemoriesOrderedByDate()
  {
    $q = new Doctrine_Query;
    $q
      ->from('UllFlowMemory x')
      ->addWhere('x.ull_flow_doc_id = ?', $this->id)
      //additional ordering by id is a hack to properly order in case of exactly the same date 
      ->orderBy('x.created_at DESC, x.id DESC')  
    ;      
     
    return $q->execute();
  }
  
  /**
   * get the ull_user_id of the currently logged in user, or '1' for the Master Admin user otherwhise
   *
   * @return int ull_user_id
   */
  protected function getUserId()
  {
    // check for symfony context (none for example for doctrine:data-load)
    if (sfContext::hasInstance())
    {
      return sfContext::getInstance()->getUser()->getAttribute('user_id', 1);
    }
    else
    {
      return 1;
    }
  }
  
  
  /**
   * Load Defaults for a new UllFlowDoc object.
   * 
   * This needs to be executed manually, because it's difficult to 
   * overwrite the constructor of Doctrine_Records
   *
   */
  public function loadDefaults()
  {
    if (!$this->ull_flow_action_id)
    {
      $this->UllFlowAction = Doctrine::getTable('UllFlowAction')->findOneBySlug('save_close');
    }
    
    if (!$this->assigned_to_ull_entity_id)
    {
      $this->assigned_to_ull_entity_id = $this->getUserId(); 
    }

    if (!$this->assigned_to_ull_flow_step_id)
    {
      $this->UllFlowStep = $this->UllFlowApp->findStartStep();
    }
  }

  
  /**
   * Get the latest saved non status-only memory
   * for the current doc
   *
   * @return UllFlowMemory
   */
  public function findLatestNonStatusOnlyMemory() 
  {
    $q = new Doctrine_Query;
    
    $q
      ->from('UllFlowMemory m, m.UllFlowAction a')
      ->where('m.ull_flow_doc_id = ?', $this->id)
      ->addWhere('a.is_status_only = ?', false)
      ->orderBy('m.created_at DESC')
      // add order by id to order correctly memories created at exactly the same time
      ->addOrderBy('m.id DESC')
    ;
    
    $memory = $q->execute()->getFirst();

    return $memory;
  }
  
  
  /**
   * Get the latest memory
   * for the current doc
   *
   * @return UllFlowMemory
   */
  public function findLatestMemory() 
  {
    $q = new Doctrine_Query;
    
    $q
      ->from('UllFlowMemory m')
      ->where('m.ull_flow_doc_id = ?', $this->id)
      ->orderBy('m.created_at DESC')
      // add order by id to order correctly memories created at exactly the same time
      ->addOrderBy('m.id DESC')
    ;
    
    $memory = $q->execute()->getFirst();

    return $memory;
  }
    
  
  /**
   * Get the previous (latest minus 1) saved non status-only memory
   * for the current doc
   *
   * @return UllFlowMemory
   */
  public function findPreviousNonStatusOnlyMemory() 
  {
    $q = new Doctrine_Query;
    
    $q
      ->from('UllFlowMemory m, m.UllFlowAction a')
      ->where('m.ull_flow_doc_id = ?', $this->id)
      ->addWhere('a.is_status_only = ?', false)
      ->orderBy('m.created_at DESC')
      // add order by id to order correctly memories created at exactly the same time
      ->addOrderBy('m.id DESC')
      ->limit(2)
    ;
    
    $rs = $q->execute();
    
    if (isset($rs[1]))
    {
      return $rs[1];
    }
  }  
  
  /**
   * Check access for the current UllFlowDoc
   * 
   * returns 'w' for write access, 'r' for read access or false for no access
   *
   * @return mixed 'w', 'r' or false
   */
  public function checkAccess()
  {
    if (sfContext::getInstance()->getUser()->hasAttribute('user_id'))
    {
      if (UllUserTable::hasGroup('MasterAdmins'))
      {
        return 'w';
      }
      
      // app-specific global write access
      if (UllUserTable::hasPermission('UllFlow_' . $this->UllFlowApp->slug . '_global_write'))
      {
        return 'w';
      }    
      
      // a user has write access to docs which are assigned to him
      if (UllEntityTable::has($this->UllEntity))
      {
        return 'w';
      }
    }
    
    // read-only access check uses the same query as the list action
    $q = new Doctrine_Query;
    $q
      ->select('x.id')
      ->from('UllFlowDoc x')
      ->addWhere('x.id = ?', $this->id)
    ;       
    $q = UllFlowDocTable::queryAccess($q, $this->UllFlowApp);
    
    if ($q->count())
    {
      return 'r';
    }
  }  
  
  /**
   * Checks access for delete action
   * 
   * Returns true if the currently logged in user is member of the MasterAdmins
   * or if he is the creator of the requested document.
   *
   * @return boolean
   */
  public function checkDeleteAccess()
  {
    
    if (UllUserTable::hasGroup('MasterAdmins'))
    {
      return true;
    }

    // Allow access if the logged in user is the creator
    $userId = sfContext::getInstance()->getUser()->getAttribute('user_id');
    $q = new Doctrine_Query;
    $q
      ->from('UllFlowDoc d')
      ->where('d.id = ?', $this->id)
      ->addWhere('d.creator_user_id = ?', $userId)
    ;       
    
    if ($q->count())
    {
      return true;
    }
  }

  
  /**
   * Returns the URI to the edit action of the current model
   * 
   * @return string A symfony URI
   */
  public function getEditUri()
  {
    return 'ullFlow/edit?doc=' . $this->id;
  }  
  
  
  /**
   * create UllFlowMemory entry
   *
   * @return integer index of new UllFlowMemory
   */
  protected function createMemory()
  {
    $i = count($this->UllFlowMemories);
    $this->UllFlowMemories[$i]->ull_flow_step_id = $this->assigned_to_ull_flow_step_id;
    // Use the doc's action as memory action by default, except a special memory action is given
    $this->UllFlowMemories[$i]->ull_flow_action_id = ($this->getMemoryAction()) ? $this->getMemoryAction() : $this->ull_flow_action_id;
    $this->UllFlowMemories[$i]->assigned_to_ull_entity_id = $this->assigned_to_ull_entity_id;

    // get creator_ull_entity from previous non status-only memory
    $prevMemory = $this->findLatestNonStatusOnlyMemory();
    if ($prevMemory)
    {
      $this->UllFlowMemories[$i]->creator_ull_entity_id = $prevMemory->assigned_to_ull_entity_id;
    }
    else
    { 
      $this->UllFlowMemories[$i]->creator_ull_entity_id = $this->getUserId();
    }
  
    $this->UllFlowMemories[$i]->comment = $this->memoryComment;        
    
    return $i;
  }

  /**
   * Create first UllFlowMemory entry
   * 
   * sets the action to create
   * removes the comment to avoid duplication
   * Sets the creator as assigned to
   * Sets the step to the startstep
   *
   */
  protected function createFirstMemory()
  {
    $i = $this->createMemory();
    $this->UllFlowMemories[$i]->UllFlowAction = Doctrine::getTable('UllFlowAction')->findOneBySlug('create');
    $this->UllFlowMemories[$i]->comment = '';
    $this->UllFlowMemories[$i]->assigned_to_ull_entity_id = $this->getUserId();
    $this->UllFlowMemories[$i]->ull_flow_step_id = $this->UllFlowApp->findStartStep()->id;
  }
  
  /**
   * Performs a workflow action and saves
   * 
   * @param UllFlowAction $ullFlowAction
   * @param array $ullFlowActionHandlerValues
   */
  public function performActionAndSave(UllFlowAction $ullFlowAction, $ullFlowActionHandlerValues = array())
  {
    $this->performAction($ullFlowAction, $ullFlowActionHandlerValues);
    
    $this->save();
  }
  
  
  /**
   * Performs a workflow action
   * 
   * Parses the rules, and sends emails
   * 
   * @param UllFlowAction $ullFlowAction
   * @param array $ullFlowActionHandlerValues
   */
  public function performAction(UllFlowAction $ullFlowAction, $ullFlowActionHandlerValues = array())
  {
    // Load defaults, as they are not loaded yet (preInsert), but necessary
    // for the "next" handling
    if (!$this->exists())
    {
      $this->loadDefaults();
    }
    
    $this->setUllFlowActionWithStatusOnlyDetection($ullFlowAction);
    
    if (!$ullFlowAction->is_status_only)
    {
      $ullFlowActionHandler = $this->buildUllFlowActionHandler($ullFlowAction, $ullFlowActionHandlerValues);
      $this->setNext($ullFlowAction, $ullFlowActionHandler);
      $this->sendMails($ullFlowAction, $ullFlowActionHandler);
    }
  }
  
  /**
   * Build the UllFlowActionHandler for a given UllFlowAction
   * @param UllFlowAction $ullFlowAction
   * @param array $ullFlowActionHandlerValues
   * 
   * @return unknown
   */
  protected function buildUllFlowActionHandler(UllFlowAction $ullFlowAction, $ullFlowActionHandlerValues)
  {
    $className = 'ullFlowActionHandler' . sfInflector::camelize($ullFlowAction->slug);
    
    // mock generator
    $generator = new ullFlowGenerator($this->UllFlowApp, $this, 'w', 'edit');
    $generator->buildForm($this);
    $generator->buildListOfUllFlowActionHandlers();
    
    // mock / inject ullFlowActionHandlerValues
    $form = $generator->getForm();
    $values = array_merge($form->getDefaults(), $ullFlowActionHandlerValues);
    unset($values['_tags']); // workaround
    
    $form->bind($values);
    if (!$form->isValid())
    {
      throw new InvalidArgumentException('Mock form for UllFlowActionHandlers has a validation error: ' . ullCoreTools::debugFormError($form, true));
    }
    
    $handler = new $className($generator);    
    
    return $handler;
  }
  
  /**
   * Parses the app's rules and sets the next entity and step accordingly
   * 
   * @param UllFlowAction $ullFlowAction
   * @param UllFlowActionHandler $ullFlowActionHandler
   */
  protected function setNext(UllFlowAction $ullFlowAction, UllFlowActionHandler $ullFlowActionHandler)
  {
    // Step One: get information about "next" from rule script
    // This is optional. The rule script can, but is not obligated to
    // set the next entity or step.
    $className = 'ullFlowRule' . sfInflector::camelize($this->UllFlowApp->slug);
    $rule = new $className($this);
    $next = $rule->getNext();
    
    // Step two: if the rule script did not supply an entity or a step
    // we use the default behaviour of the ullFlow action (e.g. "reopen")
    if (!isset($next['entity']) || !isset($next['step']))
    {
      $next = array_merge($ullFlowActionHandler->getNext(), $next);
    }
    
    // Now update the object only for next parts which have been modified,
    // otherwise leave them as they were
    if (isset($next['entity'])) 
    {
      $this->UllEntity = $next['entity'];
    }
    
    if (isset($next['step']))
    {
      $this->UllFlowStep = $next['step'];
    }
  }
  
  
  /**
   * Send notify emails
   * 
   * @param UllFlowAction $ullFlowAction
   * @param UllFlowActionHandler $ullFlowActionHandler
   */
  protected function sendMails(UllFlowAction $ullFlowAction, UllFlowActionHandler $ullFlowActionHandler)
  {
    // We need the id for the mails
    if (!$this->exists())
    {
      $this->save(null, false);
    }
    
    if ($ullFlowAction->is_notify_next)
    {
      $mail = new ullFlowMailNotifyNext($this);
      $mail->send();
    }

    if ($ullFlowAction->is_notify_creator)
    {
      $mail = new ullFlowMailNotifyCreator($this);
      $mail->send();
    }

    $ullFlowActionHandler->sendMail();
  }  
  
  
}