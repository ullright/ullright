<?php

class ullFlowGenerator extends ullGenerator
{
  protected
    $formClass = 'ullFlowForm',
    $app,
    $ullFlowActionHandlers = array(),
    $ullFlowActionHandler
  ;

  /**
   * Constructor
   *
   * @param UllFlowApp $app
   * @param string $defaultAccess can be "r" or "w" for read or write
   */
  public function __construct($app = null, $defaultAccess = 'r')
  {
    if ($app)
    {
      if (!$app->exists())
      {
        throw new InvalidArgumentException('Invalid UllFlowApp: it does not exist');
      }
      
      $this->app = $app;
    }
    
    parent::__construct($defaultAccess);
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
        $this->app, $this->defaultAccess, $this->requestAction);
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
        //workaround so we can access the column 'options' 
        $stepAction->setAttribute(Doctrine::ATTR_AUTO_ACCESSOR_OVERRIDE, false); 
        
        $ullFlowActionSlug = $stepAction->UllFlowAction->slug;
        $ullFlowActionHandlerName = 'ullFlowActionHandler' . sfInflector::camelize($ullFlowActionSlug);
        $this->ullFlowActionHandlers[$ullFlowActionSlug] = new $ullFlowActionHandlerName($this->getForm(), $stepAction->options);     
      }
    }
    
    //master admin always allow assign to
    if (UllUserTable::hasGroup('MasterAdmins') &&
      !in_array('assign_to_user', $this->ullFlowActionHandlers))
    {
      $this->ullFlowActionHandlers['assign_to_user'] = new ullFlowActionHandlerAssignToUser($this->getForm());
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
      
      $this->ullFlowActionHandler = new $ullFlowActionHandlerName($this->getForm());
      
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