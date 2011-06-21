<?php

class BaseUllFlowDocColumnConfigCollection extends ullColumnConfigCollection
{
  protected 
    $app,
    $doc,
    $defaultListColumns = array(
      'subject',
      'priority',
      'due_date',
      'creator_user_id',
      'created_at',
    )
  ;

  public function __construct($modelName, $app, $doc, $defaultAccess = null, $requestAction = null)
  {
    $this->app = $app;
    $this->doc = $doc;
    
    parent::__construct($modelName, $defaultAccess, $requestAction);
  }

  public static function build($app, $doc, $defaultAccess = null, $requestAction = null)
  {
    // Check for custom app column config collection to add e.g. app specific column access rights
    // Example: 
// apps/frontend/lib/generator/columnConfigCollection/UllFlowDocTroubleTicketColumnConfigCollection.class.php
//<?php
//
//class UllFlowDocTroubleTicketColumnConfigCollection extends UllFlowDocColumnConfigCollection
//{
//  protected function applyCustomSettings()
//  {
//    parent::applyCustomSettings();
//    
//    // add custom code here... 
//  }  
//}
    $className = 'UllFlowDoc' . sfInflector::classify($app['slug']) . 'ColumnConfigCollection';
    
    if (!class_exists($className))
    {
      $className = 'UllFlowDocColumnConfigCollection';
    }
    
    $c = new $className('UllFlowDoc', $app, $doc, $defaultAccess, $requestAction);
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
    
    $this['ull_project_id']
      ->setLabel(__('Project', null, 'ullTimeMessages'))
    ;

    $this['subject']
      ->setLabel(__('Subject', null, 'common'))
      ->setMetaWidgetClassName('ullMetaWidgetLink')
    ;

    $this['priority']
      ->setLabel(__('Priority', null, 'common'))
      ->setMetaWidgetClassName('ullMetaWidgetPriority')
    ;
    
    $this['due_date']
      ->setLabel(__('Due date', null, 'common'))
      ->setMetaWidgetClassName('ullMetaWidgetDateTime')
      ->setWidgetOption('act_as_due_date', true)
    ;

    $this['ull_flow_action_id']
      ->setLabel(__('Status', null, 'common'))
      ->setMetaWidgetClassName('ullMetaWidgetUllFlowAction')
    ;

    $this['assigned_to_ull_entity_id']
      ->setLabel(__('Assigned to', null, 'ullFlowMessages'))
      ->setMetaWidgetClassName('ullMetaWidgetUllEntity')
    ;
    
    $this['assigned_to_ull_flow_step_id']
      ->setLabel(__('Step', null, 'ullFlowMessages'))
    ;      
    
    $this['created_at']->setMetaWidgetClassName('ullMetaWidgetDate');    
    
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
       

      
      $this->enable(array('creator_user_id', 'created_at'));
    }
  
    if ($this->isCreateOrEditAction())
    {
      // Hide native UllFlowDoc columns
      $this->disableAllExcept(array());
      
        $this->create('memory_comment')
          ->setWidgetAttribute('size', 50)
          ->setIsArtificial(true)
          ->setAutoRender(false)
        ;
        
        $this->create('duration_seconds')
          ->setMetaWidgetClassName('ullMetaWidgetTime')
          ->setWidgetOption('fragmentation', 5)
          ->setIsArtificial(true)
          ->setAutoRender(false)
        ;

        $this->create('effort_date')
          ->setMetaWidgetClassName('ullMetaWidgetDate')
          ->setWidgetAttribute('size', 10)
          ->setIsArtificial(true)
          ->setAutoRender(false)
        ;        
    }
    
    $this->addVirtualColumns();
    
    $this->handleListColumns();
    
    $this->handleAssignmentOverview();
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
        // same for due_date
        if ($this->app || (!$this->app && !$column['is_subject'] && !$column['is_due_date']))
        {
          $this->create($columnName)
            ->setModelName($this->getModelName())
            ->setLabel($column->label)
            ->setMetaWidgetClassName($column->UllColumnType->class)
            ->setWidgetOptions(sfToolkit::stringToArray($column->options))
            ->setValidatorOption('required', $column->is_mandatory)
          ;

          if (!$column->is_enabled)
          {
            $this[$columnName]->setCustomAttribute('disabled_override', true);
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
        //handle manual ullFlowColumnConfig override
        if ($columnConfig->getCustomAttribute('disabled_override'))
        {
          continue;
        }
        
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
  
  /**
   * handleAssignmentOverview
   */
  protected function handleAssignmentOverview()
  {
    if ($this->isAction('assignmentOverview'))
    {
      $this['assigned_to_ull_entity_id']
        ->enable()
        ->setAutoRender(false)
      ;
      
      $columns = array(
        'ull_flow_app_id',
        'subject',
        'priority',
        'due_date',
        'creator_user_id',
        'created_at',
        'assigned_to_ull_entity_id',
      );
      
      $this->enable($columns);
      
      $this->disableAllExcept($columns);
      
      $this->order($columns);
    }
  }
  
  /**
   * Check if we are in a given step
   * 
   * @param string $slug
   * @return boolean
   */
  public function isStep($slug)
  {
    if ($this->doc && $this->doc->UllFlowStep->slug == $slug)
    {
      return true;
    }
    
    return false;
  }
}