<?php

class ullTableTool
{
  protected
    $tableConfig    = array(),
    $columnsConfig  = array(),
    $forms          = array(),
    $rows           = array(),
    $modelName,
    $isBuilt        = false,
    $defaultAccess,
    $columnsBlacklist = array(
        'namespace',
        'type',
    ),
    // columns which should be displayed last (at the bottom of the edit template)
    $columnsOrderBottom = array(
        'creator_user_id',
        'created_at',
        'updator_user_id',
        'updated_at',
    ),
    $columnsReadOnly = array(
        'creator_user_id',
        'created_at',
        'updator_user_id',
        'updated_at',
    ) 
  ;
  
  public function __construct($modelName = null, $defaultAccess = 'r')
  {

    if ($modelName === null)
    {
      throw new InvalidArgumentException('A model must be supplied');
    }
    
    if (!class_exists($modelName))
    {
      throw new InvalidArgumentException('Invalid model: ' . $modelName);
    }
    
//    var_dump($this->rows);
//    die;
    
    $this->modelName = $modelName;
    
    $this->setDefaultAccess($defaultAccess);
    
    $this->buildTableConfig();
    
    $this->buildColumnsConfig();
    
//    $this->buildForm();
  }

  public function getModelName()
  {
    return $this->modelName;
  }

  // makes no sense as a public function because it doesn't rebuild the columnsConfig etc
  protected function setDefaultAccess($access = 'r')
  {
    if (!in_array($access, array('r', 'w')))
    {
      throw new UnexpectedValueException('Invalid access type "'. $access .'. Has to be either "r" or "w"'); 
    }

    $this->defaultAccess = $access;
  }  

  public function getDefaultAccess()
  {
    return $this->defaultAccess;
  }  

  public function getTableConfig()
  {
    return $this->tableConfig;
  }
  
  public function getColumnsConfig()
  {
    return $this->columnsConfig;
  }
  
  public function getForm()
  {
    if (!$this->isBuilt)
    {
      throw new RuntimeException('You have to call buildForm() first');
    }     
    
    return $this->forms[0];
  }
  
  public function getForms()
  {
    if (!$this->isBuilt)
    {
      throw new RuntimeException('You have to call buildForm() first');
    }     
    
    return $this->forms;
  }
  
  public function getRow()
  {
    if (!$this->isBuilt)
    {
      throw new RuntimeException('You have to call buildForm() first');
    } 
        
    return $this->rows[0];
  }
  
  public function getLabels()
  {
    $lables = array();
    foreach ($this->columnsConfig as $columnName => $columnConfig)
    {
      if (isset($columnConfig['access']))
      {
        $lables[$columnName] = $columnConfig['label'];
      }
    }
    return $lables;
  }
  
  public function getIdentifierUrlParams($row)
  {
    if (!is_integer($row)) 
    {
      throw new UnexpectedArgumentException('$row must be an integer: ' . $row);
    }
    
    if (!$this->isBuilt)
    {
      throw new RuntimeException('You have to call buildForm() first');
    }
    
    $array = array();
    foreach ($this->getIdentifierAsArray() as $identifier)
    {
      $array[] = $identifier . '=' . $this->rows[$row]->$identifier;
    }
    
    return implode('&', $array);
  }
  
  protected function getIdentifierAsArray()
  {
    $identifier = $this->tableConfig->getIdentifier();
    if (!is_array($identifier))
    {
      $identifier = array(0 => $identifier);
    }
    return $identifier;
  }
  
  protected function buildTableConfig()
  {
    $tableConfig = Doctrine::getTable('UllTableConfig')->
        findOneByDbTableName($this->modelName);
        
    if (!$tableConfig)
    {
      $tableConfig = new UllTableConfig;
      $tableConfig->db_table_name = $this->modelName;
//      $tableConfig->save();
    }
    
    $this->tableConfig = $tableConfig;
  }
  
  protected function buildColumnsConfig()
  {
    // get Doctrine relations
    $relations = Doctrine::getTable($this->modelName)->getRelations();

    $columnRelations = array();
    
    foreach ($relations as $relation) {
      $columnRelations[$relation->getLocal()] = array(
          'model' => $relation->getClass(), 
          'foreign_id' => $relation->getForeign()
      );
    }
//    var_dump($relations);
//    var_dump($columnRelations);
//    die;
//    

//    var_dump($this->rows[0]->getTable()->getColumns());
//    die;
    
    // loop through table (Doctrine) columns
    foreach (Doctrine::getTable($this->modelName)->getColumns() as $columnName => $column)
    {
      $columnConfig = array(
        'widgetOptions'      => array(),
        'widgetAttributes'   => array(),
        'validatorOptions'   => array(),
      );
      
      // set defaults
      $columnConfig['label']        = sfInflector::humanize($columnName);
      $columnConfig['metaWidget']   = 'ullMetaWidgetString';
      $columnConfig['access']       = $this->defaultAccess;
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
      
      
      // set relations if not the primary key
      if (!isset($column['primary']) or $columnName != 'id')
      {
        if (isset($columnRelations[$columnName]))
        {
          $columnConfig['metaWidget'] = 'ullMetaWidgetForeignKey';
          $columnConfig['relation'] = $columnRelations[$columnName];
        }
      }      
      
      // parse ullColumnConfigData table
      $dbColumnConfig = UllColumnConfigTable::getColumnConfigArray($this->modelName, $columnName);
      $columnConfig = array_merge($columnConfig, $dbColumnConfig);
      
//      var_dump($column);
      
      // TODO: more defaults (humanized default label names, ...)
      
      // TODO: more Doctrine column config (widget by column type, primary keys, unique, notnull, ...)

      // TODO: more column config by table ullColumnConfig
      
      // TODO: handle default "ullRecord" columns like created_by, Namespace etc...
      
      $this->columnsConfig[$columnName] = $columnConfig;
    }
    
    $this->removeBlacklistColumns();
    
    $this->setReadOnlyColumns();
      
    $this->sortColumns();
//    var_dump($this->columnsConfig);
//    die;    
  }
  
  public function buildForm($rows)
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
    
    foreach ($this->rows as $key => $row) 
    {
      $this->forms[$key] = new ullForm($row);
      foreach ($this->columnsConfig as $columnName => $columnConfig)
      {
        if (isset($columnConfig['access'])) {
        
          $ullMetaWidgetClassName = $columnConfig['metaWidget'];
          $ullMetaWidget = new $ullMetaWidgetClassName($columnConfig);
          $this->forms[$key]->addUllMetaWidget($columnName, $ullMetaWidget);
          
//          $this->forms[$key]->setDefault($columnName, $this->rows[$key]->$columnName);
          $this->forms[$key]->getWidgetSchema()->setLabel($columnName, $columnConfig['label']);
        }        
      }
    }
    
    $this->isBuilt = true;
  }
  
  protected function removeBlacklistColumns()
  {
    foreach ($this->columnsBlacklist as $column)
    {
      unset($this->columnsConfig[$column]);
    }
  }
  
  protected function setReadOnlyColumns()
  {
    foreach($this->columnsReadOnly as $column)
    {
      $this->columnsConfig[$column]['access'] = 'r';
    }
  }
  
  protected function sortColumns()
  {
    $bottom = array();
    foreach ($this->columnsOrderBottom as $column)
    {
      $bottom[$column] = $this->columnsConfig[$column];
      unset($this->columnsConfig[$column]);
    }
    
    $this->columnsConfig = array_merge($this->columnsConfig, $bottom);
  }
  
  
}

?>