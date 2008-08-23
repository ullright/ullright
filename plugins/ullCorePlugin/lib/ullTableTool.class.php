<?php

class ullTableTool
{
  protected
    $columnsConfig  = array(),
    $rows           = array(),
    $modelName,
    $forms          = array()
    
  ;
  
  public function __construct($rows)
  {
    if (is_array($rows))
    {
      $this->rows = $rows;
    }
    else
    {
      $this->rows[] = $rows;
    }
    
    $this->modelName = get_class($this->rows[0]);
    
    $this->buildColumnsConfig();
    
    $this->buildForm();
  }
  
  public function getForm()
  {
    return $this->forms[0];
  }
  
  protected function buildColumnsConfig()
  {
    foreach ($this->rows[0]->getTable()->getColumns() as $columnName => $column)
    {
      
      // create columnConfig and set defaults
      $columnConfig = array(
          'label'     => sfInflector::humanize($columnName),
          'metaWidget'    => 'ullMetaWidgetString',
          'access'    => 'w',
      );
      
      // Doctrine config
      if (isset($column['primary']))
      {
        $columnConfig['access'] = 'r';
      }
      
      switch ($column['type'])
      {
        case 'string':
          $columnConfig['metaWidget'] = 'ullMetaWidgetString'; 
          $columnConfig['size']       = $column['length'];
          break;
          
        case 'integer':
          $columnConfig['metaWidget'] = 'ullMetaWidgetInteger';
          break;
          
        case 'timestamp':
          $columnConfig['metaWidget'] = 'ullMetaWidgetDateTime';
          break;
      }
      
//      var_dump($column);
      
      // TODO: more defaults (humanized default label names, ...)
      
      // TODO: more Doctrine column config (widget by column type, primary keys, unique, notnull, ...)

      // TODO: more column config by table ullColumnConfig
      
      // TODO: handle default "ullRecord" columns like created_by, Namespace etc...
      
      $this->columnsConfig[$columnName] = $columnConfig;
    }
//    var_dump($this->columnsConfig);
//    die;    
  }
  
  protected function buildForm()
  {
    // TODO: handle multiple rows
    
    $this->forms[0] = new ullForm();
    
    foreach($this->columnsConfig as $columnName => $columnConfig)
    {
      //TODO: check if column enabled
      
      $ullWidgetWrapperClassName = $columnConfig['metaWidget'];
      $ullWidgetWrapper = new $ullWidgetWrapperClassName($columnConfig);
      $this->forms[0]->addUllWidgetWrapper($columnName, $ullWidgetWrapper);
      
      $this->forms[0]->setDefault($columnName, $this->rows[0]->$columnName);
      
    }
    
//    var_dump($form);
//    die;
  }
  
  
  
}

?>