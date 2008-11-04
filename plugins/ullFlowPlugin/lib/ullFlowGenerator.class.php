<?php

class ullFlowGenerator extends ullGenerator
{
  protected
    $formClass = 'ullFlowForm',
    $app
  ;

  /**
   * Constructor
   *
   * @param UllFlowApp $app
   * @param string $defaultAccess can be "r" or "w" for read or write
   */
  public function __construct(UllFlowApp $app, $defaultAccess = 'r')
  {

    if (!$app->exists())
    {
      throw new InvalidArgumentException('Invalid UllFlowApp: it does not exist');
    }
    
    $this->app = $app;
    
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
  
  /**
   * builds the table config
   *
   * @throws: InvalidArgumentException
   */
  protected function buildTableConfig()
  {
    $this->tableConfig = $this->app;
  }
  
  /**
   * builds the column config
   *
   */
  protected function buildColumnsConfig()
  {
    $dbColumnConfig = $this->app->UllFlowColumnConfigs;
    
    if ($this->requestAction == 'list')
    {
      $this->columnsConfig['id'] = array(
        'widgetOptions'      => array(),
        'widgetAttributes'   => array(),
        'validatorOptions'   => array(),
        'label' => 'ID',
        'metaWidget' => 'ullMetaWidgetInteger',
        'access' => $this->defaultAccess,
        'show_in_list' => true,
      );      
      $this->columnsConfig['ull_flow_app_id'] = array(
        'widgetOptions'      => array(),
        'widgetAttributes'   => array(),
        'validatorOptions'   => array(),
        'label' => 'Application',
        'metaWidget' => 'ullMetaWidgetForeignKey',
        'access' => $this->defaultAccess,
        'show_in_list' => true,
      	'relation' => array('model' => 'UllFlowApp', 'foreign_id' => 'id')
      );
    }

    $columns = array();
    
    foreach ($dbColumnConfig as $config)
    {
      $columns[$config->slug] = $config;  
    }
    

    
    // loop through table (Doctrine) columns
    foreach ($columns as $columnName => $column)
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
      $columnConfig['show_in_list'] = $column->show_in_list;
      $columnConfig['validatorOptions']['required'] = $column->mandatory;
      
      $this->columnsConfig[$columnName] = $columnConfig;
    }
    
    if ($this->requestAction == 'list')
    {
      $this->columnsConfig['assigned_to_ull_entity_id'] = array(
        'widgetOptions'      => array(),
        'widgetAttributes'   => array(),
        'validatorOptions'   => array(),
        'label' => 'Assigned to',
        'metaWidget' => 'ullMetaWidgetForeignKey',
        'access' => $this->defaultAccess,
        'show_in_list' => true,
      	'relation' => array('model' => 'UllEntity', 'foreign_id' => 'id')
      );
      $this->columnsConfig['creator_user_id'] = array(
        'widgetOptions'      => array(),
        'widgetAttributes'   => array(),
        'validatorOptions'   => array(),
        'label' => 'Created by',
        'metaWidget' => 'ullMetaWidgetForeignKey',
        'access' => $this->defaultAccess,
        'show_in_list' => true,
      'relation' => array('model' => 'UllUser', 'foreign_id' => 'id')
      );
      $this->columnsConfig['created_at'] = array(
        'widgetOptions'      => array(),
        'widgetAttributes'   => array(),
        'validatorOptions'   => array(),
        'label' => 'Created at',
        'metaWidget' => 'ullMetaWidgetDateTime',
        'access' => $this->defaultAccess,
        'show_in_list' => true,
      );            
    }
//    var_dump($this->columnsConfig);
//    die; 
   
  }
  
}