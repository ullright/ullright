<?php

class ullFlowGenerator extends ullGenerator
{
  protected
    $formClass = 'ullFlowForm',
    $app,
    $ullFlowActionHandlers = array(),
    $ullFlowActionHandler,
    $defaultListColumns = array(
      'ull_flow_app_id',      
      'subject',
      'priority',
      'creator_user_id',
      'created_at',
    )
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
   * get array containing the active columns
   *
   * @return array active columns
   */
  public function getActiveColumns()
  {
    if ($this->activeColumns)
    {
       return $this->activeColumns;
    }
    
    if ($this->getRequestAction() == "list")
    {
      $this->activeColumns = array();
   
      // get selections and order of columns from UllFlowApp
      if (isset($this->app) && $columnList = $this->app->list_columns)
      {
        $columnList = explode(',', $columnList);
      }
      else
      {
        $columnList = $this->defaultListColumns;
      }
      
//      var_dump($columnList);
      
      foreach($columnList as $column)
      {
        if (isset($this->columnsConfig[$column]) and $this->isColumnEnabled($this->columnsConfig[$column])) 
        {
          $this->activeColumns[$column] = $this->columnsConfig[$column];
        }
      }
      
//      var_dump($this->activeColumns);die;
      
      return $this->activeColumns;
    }
    else
    {
      return parent::getActiveColumns();
    }
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
    foreach ($this->getRow()->UllFlowStep->UllFlowStepActions as $stepAction) 
    {
      $ullFlowActionSlug = $stepAction->UllFlowAction->slug;
      $ullFlowActionHandlerName = 'ullFlowActionHandler' . sfInflector::camelize($ullFlowActionSlug);
      $this->ullFlowActionHandlers[$ullFlowActionSlug] = new $ullFlowActionHandlerName($this->getForm(), $stepAction->options);     
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