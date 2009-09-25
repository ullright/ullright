<?php

class UllFlowDocColumnConfigCollection extends ullColumnConfigCollection
{
  protected 
    $app,
    $defaultListColumns = array(
      'subject',
      'priority',
      'creator_user_id',
      'created_at',
    )
  ;

  public function __construct($modelName, $app, $defaultAccess = null, $requestAction = null)
  {
    $this->app = $app;
    parent::__construct($modelName, $defaultAccess, $requestAction);
  }

  public static function build($app, $defaultAccess = null, $requestAction = null)
  {
    $c = new self('UllFlowDoc', $app, $defaultAccess, $requestAction);
    $c->buildCollection();

    return $c;
  }  

  /**
   * Applies model specific custom column configuration
   *
   */
  protected function applyCustomSettings()
  {
    $this['ull_flow_app_id']
      ->setLabel(__('Workflow', null, 'ullFlowMessages'))
    ;    
    
    if ($this->isListAction())
    {
      if (!$this->app)
      {
        $this['ull_flow_app_id']
          ->setLabel(__('WF', null, 'ullFlowMessages') . '.')
          ->setMetaWidgetClassName('ullMetaWidgetUllFlowApp')
        ;
      }
      else
      {
        $this->disable(array('ull_flow_app_id'));
      }
       
      $this['subject']
        ->setLabel(__('Subject', null, 'common'))
        ->setMetaWidgetClassName('ullMetaWidgetLink')
      ;

      $this['priority']
        ->setLabel(__('Priority', null, 'common'))
        ->setMetaWidgetClassName('ullMetaWidgetPriority')
      ;

      $this['ull_flow_action_id']
        ->setLabel(__('Status', null, 'common'))
        ->setMetaWidgetClassName('ullMetaWidgetUllFlowAction')
      ;

      $this['assigned_to_ull_entity_id']
        ->setLabel(__('Assigned to', null, 'ullFlowMessages'))
        ->setMetaWidgetClassName('ullMetaWidgetUllEntity')
      ;
      
      $this['created_at']->setMetaWidgetClassName('ullMetaWidgetDate');
      
      $this->enable(array('creator_user_id', 'created_at'));
    }
  
    if ($this->isCreateOrEditAction())
    {
      $this->disableAllExcept(array());
    }
    
    $this->addVirtualColumns();
    
    $this->handleListColumns();
  }
  
  
  /**
   * Add virtual columns
   * 
   * @return none
   */
  protected function addVirtualColumns()
  {
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
          $this->create($columnName)
            ->setLabel($column->label)
            ->setMetaWidgetClassName($column->UllColumnType->class)
            ->setWidgetOptions(sfToolkit::stringToArray($column->options))
            ->setValidatorOption('required', $column->is_mandatory)
          ;
            
          if ($this->isListAction() && !$column->is_in_list)
          {
            $this[$columnName]->disable();
          }            
          
          if ($column->default_value !== null)
          {
            $this[$columnName]->setDefaultValue($column->default_value);
          }
        }
      }
    }    
  }
  
  
  
  /**
   * Handle list columns
   * 
   * @return none
   */
  protected function handleListColumns()
  {
    if ($this->isListAction())
    {
      if ($this->app)
      {
        if ($columns = $this->app->list_columns)
        {
          $listColumns = explode(',', $columns);
        }
        else
        {
          $listColumns = $this->defaultListColumns;
        }
      }
      else
      {
        $listColumns = array_merge(array('ull_flow_app_id'), $this->defaultListColumns);
      }
      
      foreach ($this->collection as $key => $columnConfig)
      {
        if (in_array($key, $listColumns))
        {
          $columnConfig->setAccess('r');
        }  
        else
        {
          $columnConfig->disable();
        }
      }
      
      $this->order($listColumns);
    }  
  }
}