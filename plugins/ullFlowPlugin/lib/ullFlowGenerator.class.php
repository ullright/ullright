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
  
  private function buildTempColumnConfig($columnName, $metaWidgetClassName, $access, $isInList = true, $relation = null)
  {
    $tempCC = new ullColumnConfiguration();
    $tempCC->setLabel($columnName);
    $tempCC->setMetaWidgetClassName($metaWidgetClassName);
    $tempCC->setAccess($access);
    $tempCC->setIsInList($isInList);
    $tempCC->setRelation($relation);
    
    return $tempCC;
  }
  
  /**
   * builds the column config
   *
   */
  protected function buildColumnsConfig()
  {
    if ($this->requestAction == 'list')
    {
      $this->columnsConfig['id'] = $this->buildTempColumnConfig(__('ID', null, 'common'),
        'ullMetaWidgetInteger', $this->defaultAccess);

      if (!$this->app) 
      {      
        $this->columnsConfig['ull_flow_app_id'] = $this->buildTempColumnConfig(__('App', null, 'common'),
          'ullMetaWidgetUllFlowApp', $this->defaultAccess, true, array('model' => 'UllFlowApp', 'foreign_id' => 'id'));
      }
     
      $this->columnsConfig['subject'] = $this->buildTempColumnConfig(__('Subject', null, 'common'),
        'ullMetaWidgetLink', $this->defaultAccess);

      $this->columnsConfig['priority'] = $this->buildTempColumnConfig(__('Priority'),
        'ullMetaWidgetPriority', $this->defaultAccess);
      
      $this->columnsConfig['ull_flow_action_id'] = $this->buildTempColumnConfig(__('Status'),
        'ullMetaWidgetUllFlowAction', $this->defaultAccess, true, array('model' => 'UllFlowAction', 'foreign_id' => 'id'));
      
      $this->columnsConfig['assigned_to_ull_entity_id'] = $this->buildTempColumnConfig(__('Assigned to'),
        'ullMetaWidgetUllUser', $this->defaultAccess, true, array('model' => 'UllEntity', 'foreign_id' => 'id'));
      
      $this->columnsConfig['creator_user_id'] = $this->buildTempColumnConfig(__('Created by'),
        'ullMetaWidgetUllUser', $this->defaultAccess, true, array('model' => 'UllEntity', 'foreign_id' => 'id'));
      
      $this->columnsConfig['created_at'] = $this->buildTempColumnConfig(__('Created at'),
        'ullMetaWidgetDate', $this->defaultAccess);
        
      $this->columnsConfig['updator_user_id'] = $this->buildTempColumnConfig(__('Updated at', null, 'common'),
        'ullMetaWidgetUllUser', $this->defaultAccess, true, array('model' => 'UllUser', 'foreign_id' => 'id'));
      
      $this->columnsConfig['updated_at'] = $this->buildTempColumnConfig(__('Created at'),
        'ullMetaWidgetDate', $this->defaultAccess);               
    }

    if ($this->app)
    {
      $dbColumnConfig = $this->app->findOrderedColumns();

      $columns = array();
    
      foreach ($dbColumnConfig as $config)
      {
        $columns[$config->slug] = $config;  
      }
      
      // loop through columns
      foreach ($columns as $columnName => $column)
      {
        // the subject column is taken from UllFlowDoc if no app is given,
        //   therefore we need to obmit it here to prevent duplicate
        if ($this->app || (!$this->app && !$column['is_subject']))            
        {
          $columnConfig = new ullColumnConfiguration($columnName, $this->defaultAccess);
          
          // set defaults
          $columnConfig->setLabel($column->label);
          $columnConfig->setMetaWidgetClassName($column->UllColumnType->class);
          $columnConfig->setIsInList($column->is_in_list);
          $columnConfig->setWidgetOptions(sfToolkit::stringToArray($column->options));
          $columnConfig->setValidatorOption('required', $column->is_mandatory);
          if ($column->default_value)
          {
            $columnConfig->setDefaultValue($column->default_value);
          }
          
          $this->columnsConfig[$columnName] = $columnConfig;
        }
      }
    }
    
//    var_dump($this->columnsConfig);
//    die; 
   
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