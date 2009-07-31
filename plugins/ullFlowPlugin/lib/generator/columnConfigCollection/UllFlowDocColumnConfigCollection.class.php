<?php

class UllFlowDocColumnConfigCollection extends ullColumnConfigCollection
{
  protected $flowApp;

  public function __construct($modelName, $flowApp, $defaultAccess = null, $requestAction = null)
  {
    $this->flowApp = $flowApp;
    parent::__construct($modelName, $defaultAccess, $requestAction);
  }

  public static function build($flowApp, $defaultAccess = null, $requestAction = null)
  {
    $c = new self('UllFlowDoc', $flowApp, $defaultAccess, $requestAction);
    $c->buildCollection();

    return $c;
  }  

  /**
   * Applies model specific custom column configuration
   *
   */
  protected function applyCustomSettings()
  {
    if ($this->isListAction())
    {
      if (!$this->flowApp)
      {
        $this['ull_flow_app_id']
          ->setLabel(__('App', null, 'common'))
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
        ->setMetaWidgetClassName('ullMetaWidgetUllUser')
      ;
      
      $this['created_at']->setMetaWidgetClassName('ullMetaWidgetDate');
      
      $this->enable(array('creator_user_id', 'created_at'));
    }
  
    if ($this->isCreateOrEditAction())
    {
      $this->disableAllExcept(array());
    }
    
    if ($this->flowApp)
    {
      $dbColumnConfig = $this->flowApp->findOrderedColumns();

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
        if ($this->flowApp || (!$this->flowApp && !$column['is_subject']))
        {
          if (!($this->isListAction() && $column->is_in_list))
          {
            $this->create($columnName)
              ->setLabel($column->label)
              ->setMetaWidgetClassName($column->UllColumnType->class)
              ->setWidgetOptions(sfToolkit::stringToArray($column->options))
              ->setValidatorOption('required', $column->is_mandatory);
            
            if ($column->default_value)
            {
              $this[$columnName]->setDefaultValue($column->default_value);
            }
          }
        }
      }
    }
  }
}