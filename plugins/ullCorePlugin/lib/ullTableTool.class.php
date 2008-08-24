<?php

class ullTableTool
{
  protected
    $columnsConfig  = array(),
    $rows           = array(),
    $modelName,
    $forms          = array(),
    $default_access
    
  ;
  
  public function __construct($rows, $default_access = 'r')
  {

    if (is_array($rows))
    {
      $this->rows = $rows;
    }
    elseif ($rows instanceof Doctrine_Collection)
    {
      $this->rows = $rows;
    }
    else
    {
      $this->rows[] = $rows;
    }
    
//    var_dump($this->rows);
//    die;
    
    $this->modelName = get_class($this->rows[0]);
    
    $this->default_access = $default_access;
    
    $this->buildColumnsConfig();
    
    $this->buildForm();
  }
  
  public function getForm()
  {
    return $this->forms[0];
  }
  
  public function getForms()
  {
    return $this->forms;
  }
  
  protected function buildColumnsConfig()
  {
    // get Doctrine relations
    $relations = $this->rows[0]->getTable()->getRelations();
        
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
    
//    var_dump($this->rows[0]->getTable()->getColumns());
//    die;
    
    // loop through table (Doctrine) columns
    foreach ($this->rows[0]->getTable()->getColumns() as $columnName => $column)
    {
      $columnConfig = array(
        'widgetOptions'      => array(),
        'widgetAttributes'   => array(),
        'validatorOptions'   => array(),
      );
      
      // create columnConfig and set defaults
      $columnConfig['label']        = sfInflector::humanize($columnName);
      $columnConfig['metaWidget']   = 'ullMetaWidgetString';
      $columnConfig['access']       = $this->default_access;
      $columnConfig['validatorOptions']['required'] = false; //must be set, as default = true
      
      switch ($column['type'])
      {
        case 'string':
          $columnConfig['metaWidget'] = 'ullMetaWidgetString'; 
          $columnConfig['widgetAttributes']['maxlength'] = $column['length'];
          $columnConfig['validatorOptions']['max_length'] = $column['length'];
          break;
          
        case 'integer':
          $columnConfig['metaWidget'] = 'ullMetaWidgetInteger';
          break;
          
        case 'timestamp':
          $columnConfig['metaWidget'] = 'ullMetaWidgetDateTime';
          break;
      }
      
      if (isset($column['notnull']))
      {
        $columnConfig['validatorOptions']['required'] = true;
      }
      
      if (isset($column['primary']))
      {
        $columnConfig['access'] = 'r';
        $columnConfig['validatorOptions']['required'] = true;
      }
      else
      // set relations if not the primary key
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
        $this->forms[$key]->getWidgetSchema()->setLabel($columnName, $columnConfig['label']);
        
      }
    }
  }
  
  
  
}

?>