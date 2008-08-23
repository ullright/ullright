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
    // get Doctrine relations
    $relations = $this->rows[0]->getTable()->getRelations();
    
    $columnRelations = array();
        
    foreach ($relations as $relation) {
      /*var $relation Doctrine_Relation_Association*/
      $columnRelations[$relation->getLocal()] = array(
          'model' => $relation->getClass(), 
          'foreign_id' => $relation->getForeign()
      );
    }
//    var_dump($relations);
//    var_dump($columnRelations);
//    die;
    
    foreach ($this->rows[0]->getTable()->getColumns() as $columnName => $column)
    {
      // create columnConfig and set defaults
      $columnConfig = array(
          'label'     => sfInflector::humanize($columnName),
          'metaWidget'    => 'ullMetaWidgetString',
          'access'    => 'w',
      );
      
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
      
      if (isset($column['primary']))
      {
        $columnConfig['access'] = 'r';
      }
      else
      // set relations
      {
        if (isset($columnRelations[$columnName]))
        {
          $columnConfig['metaWidget'] = 'ullMetaWidgetForeignKey';
          $columnConfig['relation'] = $columnRelations[$columnName];
        }
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
    foreach ($this->rows as $key => $row) 
    {
      $this->forms[$key] = new ullForm();
      
      foreach ($this->columnsConfig as $columnName => $columnConfig)
      {
        //TODO: check if column enabled
        
        $ullMetaWidgetClassName = $columnConfig['metaWidget'];
        $ullMetaWidget = new $ullMetaWidgetClassName($columnConfig);
        $this->forms[$key]->addUllWidgetWrapper($columnName, $ullMetaWidget);
        
        $this->forms[$key]->setDefault($columnName, $this->rows[$key]->$columnName);
      }
    }
  }
  
  
  
}

?>