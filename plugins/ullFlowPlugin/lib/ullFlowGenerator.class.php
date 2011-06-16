<?php

class ullFlowGenerator extends ullGenerator
{
  protected
    $formClass = 'ullFlowForm',
    $app,
    $doc,
    $ullFlowActionHandlers = array(),
    $ullFlowActionHandler
  ;

  /**
   * Constructor
   *
   * @param UllFlowApp $app
   * @param UllFlowDoc $doc optional, used in edit mode for columnsConfig
   *                        to allow doc-based configurations e.g. show a field
   *                        only in a certain step
   * @param string $defaultAccess can be "r" or "w" for read or write
   */
  public function __construct($app = null, $doc = null, $defaultAccess = 'r', $requestAction = null)
  {
    if ($app)
    {
      if (!$app->exists())
      {
        throw new InvalidArgumentException('Invalid UllFlowApp: it does not exist');
      }
      
      $this->app = $app;
    }
    
    $this->doc = $doc;
    
    parent::__construct($defaultAccess, $requestAction);
  }    
  
  /**
   * returns the identifier url params
   *
   * @param integer $row numerical key / the row number
   * @return string
   */
  public function getIdentifierUrlParams($row)
  {
    return 'doc=' . $this->rows[$row]->id;
  }
  
  public function getIdentifierUrlParamsAsArray($row)
  { 
    return array('doc' => $this->rows[$row]->id);
  }  
  
  
  /**
   * builds the table config
   *
   * @throws: InvalidArgumentException
   */
  protected function buildTableConfig()
  {
    if ($this->app)
    {
//      var_dump($this->app->toArray());
      $this->tableConfig = $this->app;
    }
  }
  
  /**
   * builds the column config
   *
   */
  protected function buildColumnsConfig()
  {   
    $this->columnsConfig = UllFlowDocColumnConfigCollection::build(
        $this->app, $this->doc, $this->defaultAccess, $this->requestAction);
        
//    var_dump(ullCoreTools::debugArrayWithDoctrineRecords($this->columnsConfig, true));die;
  }
  
  /**
   * Build list of ullFlowActions
   * 
   * Loads all needed ullFlowActions and configures the ullFlowForm with the 
   * needed widgets and validators
   * 
   */
  public function buildListOfUllFlowActionHandlers()
  {
    if (UllEntityTable::has($this->getRow()->UllEntity))
    {
      foreach ($this->getRow()->UllFlowStep->UllFlowStepActions as $stepAction) 
      {
        $ullFlowActionSlug = $stepAction->UllFlowAction->slug;
        $ullFlowActionHandlerName = 'ullFlowActionHandler' . sfInflector::camelize($ullFlowActionSlug);
//        var_dump($ullFlowActionHandlerName);
//        var_dump($stepAction->options);
        $this->ullFlowActionHandlers[$ullFlowActionSlug] = new $ullFlowActionHandlerName($this, $stepAction->options);     
      }
    }
    
    //Always allow the master admin to perform "assign to" action
    if (UllUserTable::hasGroup('MasterAdmins') &&
      !in_array('assign_to_user', array_keys($this->ullFlowActionHandlers)) &&
      sfConfig::get('app_ull_flow_enable_master_admin_assign_to_user', true)
      )
    {
      $this->ullFlowActionHandlers['assign_to_user'] = new ullFlowActionHandlerAssignToUser($this);
      return;
    }
  
    //only the user to whom the document is assigned to is allowed to perform workflow actions
    if ($this->getRow()->exists() && !(UllEntityTable::has($this->getRow()->UllEntity)))
    {
      $this->ullFlowActionHandlers = array();
      return;
    }
  }    
  
  
  /**
   * Get list of ullFlowActionHandlers
   *
   * @return array
   */
  public function getListOfUllFlowActionHandlers()
  {
    return $this->ullFlowActionHandlers;
  }  
  
  /**
   * set ullFlowActions
   * 
   * Used in the "update" part of the edit action ("post request"):
   * For the selected action, the validators are set to "required"
   *
   * @param string $actionSlug
   */
  public function setUllFlowActionHandler($actionSlug)
  {
    if (UllFlowActionTable::isNonStatusOnly($actionSlug))
    {
      $ullFlowActionHandlerName = 'ullFlowActionHandler' . sfInflector::camelize($actionSlug);
      
      if (!in_array($actionSlug, array_keys($this->ullFlowActionHandlers)))
      {
        throw new InvalidArgumentException('Invalid ullFlowAction slug: ' . $actionSlug);
      }
      
      $this->ullFlowActionHandler = new $ullFlowActionHandlerName($this);
      
      // check if comment is mandatory
      $action = Doctrine::getTable('UllFlowAction')->findOneBySlug($actionSlug);
      if ($action->is_comment_mandatory)
      {
        $this->getForm()->getValidatorSchema()->offsetGet('memory_comment')->setOption('required', true);
      }
      
      
      // set required for additional ActionHandler fields
      // Example: assign_to_user select box      
      foreach ($this->ullFlowActionHandler->getFormFields() as $formField)
      {
        $this->getForm()->getValidatorSchema()->offsetGet($formField)->setOption('required', true);
      }
    }
  }  
  
  /**
   * Get ullFlowActionHandler
   *
   * @return array
   */
  public function getUllFlowActionHandler()
  {
    return $this->ullFlowActionHandler;
  }

}