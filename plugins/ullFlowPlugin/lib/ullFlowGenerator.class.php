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
//    var_dump($this->columnsConfig);
//    die;    
  }
  
}