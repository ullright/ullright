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
    if ($this->requestAction == 'list')
    {
      $this->columnsConfig['id'] = array(
        'widgetOptions'       => array(),
        'widgetAttributes'    => array(),
        'validatorOptions'    => array(),
        'label'               => __('ID', null, 'common'),
        'metaWidget'          => 'ullMetaWidgetInteger',
        'access'              => $this->defaultAccess,
        'is_in_list'          => true,
      );

      if (!$this->app) 
      {      
	      $this->columnsConfig['ull_flow_app_id'] = array(
	        'widgetOptions'       => array(),
	        'widgetAttributes'    => array(),
	        'validatorOptions'    => array(),
	        'label'               => __('App', null, 'common'),
	        'metaWidget'          => 'ullMetaWidgetUllFlowApp',
	        'access'              => $this->defaultAccess,
	        'is_in_list'          => true,
	    	  'relation'            => array('model' => 'UllFlowApp', 'foreign_id' => 'id')
	      );
      }
      
      $this->columnsConfig['subject'] = array(
        'widgetOptions'     => array(),
        'widgetAttributes'  => array(),
        'validatorOptions'  => array(),
        'label'             => __('Subject', null, 'common'),
        'metaWidget'        => 'ullMetaWidgetLink',
        'access'            => $this->defaultAccess,
        'is_in_list'        => true,
      );

      $this->columnsConfig['priority'] = array(
        'widgetOptions'     => array(),
        'widgetAttributes'  => array(),
        'validatorOptions'  => array(),
        'label'             => __('Priority'),
        'metaWidget'        => 'ullMetaWidgetPriority',
        'access'            => $this->defaultAccess,
        'is_in_list'        => true,
      );            
      $this->columnsConfig['ull_flow_action_id'] = array(
        'widgetOptions'     => array(),
        'widgetAttributes'  => array(),
        'validatorOptions'  => array(),
        'label'             => __('Status'),
        'metaWidget'        => 'ullMetaWidgetUllFlowAction',
        'access'            => $this->defaultAccess,
        'is_in_list'        => true,
        'relation'          => array('model' => 'UllFlowAction', 'foreign_id' => 'id')
      );  
      $this->columnsConfig['assigned_to_ull_entity_id'] = array(
        'widgetOptions'     => array(),
        'widgetAttributes'  => array(),
        'validatorOptions'  => array(),
        'label'             => __('Assigned to'),
        'metaWidget'        => 'ullMetaWidgetUllUser',
        'access'            => $this->defaultAccess,
        'is_in_list'        => true,
        'relation'          => array('model' => 'UllEntity', 'foreign_id' => 'id')
      );          
      $this->columnsConfig['creator_user_id'] = array(
        'widgetOptions'     => array(),
        'widgetAttributes'  => array(),
        'validatorOptions'  => array(),
        'label'             => __('Created by', null, 'common'),
        'metaWidget'        => 'ullMetaWidgetUllUser',
        'access'            => $this->defaultAccess,
        'is_in_list'        => true,
      'relation'            => array('model' => 'UllUser', 'foreign_id' => 'id')
      );
      $this->columnsConfig['created_at'] = array(
        'widgetOptions'     => array(),
        'widgetAttributes'  => array(),
        'validatorOptions'  => array(),
        'label'             => __('Created at', null, 'common'),
        'metaWidget'        => 'ullMetaWidgetDate',
        'access'            => $this->defaultAccess,
        'is_in_list'        => true,
      );
      $this->columnsConfig['updator_user_id'] = array(
        'widgetOptions'     => array(),
        'widgetAttributes'  => array(),
        'validatorOptions'  => array(),
        'label'             => __('Updated by', null, 'common'),
        'metaWidget'        => 'ullMetaWidgetUllUser',
        'access'            => $this->defaultAccess,
        'is_in_list'        => true,
      'relation'            => array('model' => 'UllUser', 'foreign_id' => 'id')
      );
      $this->columnsConfig['updated_at'] = array(
        'widgetOptions'     => array(),
        'widgetAttributes'  => array(),
        'validatorOptions'  => array(),
        'label'             => __('Updated at', null, 'common'),
        'metaWidget'        => 'ullMetaWidgetDate',
        'access'            => $this->defaultAccess,
        'is_in_list'        => true,
      );                
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
          $columnConfig = array(
            'widgetOptions'      => array(),
            'widgetAttributes'   => array(),
            'validatorOptions'   => array(),
          );
          
          // set defaults
          $columnConfig['label']        = $column->label;
          $columnConfig['metaWidget']   = $column->UllColumnType->class;
          $columnConfig['access']       = $this->defaultAccess;
          $columnConfig['is_in_list']   = $column->is_in_list;
          $columnConfig['widgetOptions']= sfToolkit::stringToArray($column->options);
          $columnConfig['validatorOptions']['required'] = $column->is_mandatory;
          if ($column->default_value)
          {
            $columnConfig['default_value']= $column->default_value;
          }
          
          $this->columnsConfig[$columnName] = $columnConfig;
        }
      }
    }
    
    //var_dump($this->columnsConfig);
    //die; 
   
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