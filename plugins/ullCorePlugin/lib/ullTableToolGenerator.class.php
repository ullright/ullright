<?php

class ullTableToolGenerator extends ullGenerator
{
  protected
    $formClass = 'ullTableToolForm',
    $modelName,
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
    ),
    $columnsNotShownInList = array(
        'creator_user_id',
        'created_at',
        'updator_user_id',
        'updated_at',
    ) 
  ;

  /**
   * Constructor
   *
   * @param string $modelName
   * @param string $defaultAccess can be "r" or "w" for read or write
   */
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
    
    $this->modelName = $modelName;
    
    parent::__construct($defaultAccess);
    
  }  
  
  /**
   * Get the model name of the data object
   *
   * @return string
   */
  public function getModelName()
  {
    return $this->modelName;
  }  
  
  /**
   * returns the identifier url params
   *
   * @param Doctrine_record $row
   * @return string
   */
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
  
  /**
   * returns the identifiers as array
   *
   * @return array
   */
  protected function getIdentifierAsArray()
  {
    $identifier = $this->tableConfig->getIdentifier();
    if (!is_array($identifier))
    {
      $identifier = array(0 => $identifier);
    }
    return $identifier;
  }
  
  /**
   * builds the table config
   *
   */
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
  
  /**
   * builds the column config
   *
   */
  protected function buildColumnsConfig()
  {
    // get Doctrine relations
    $relations = Doctrine::getTable($this->modelName)->getRelations();

    $columnRelations = array();
    
    foreach ($relations as $relation) {
      $columnRelations[$relation->getLocal()] = array(
//          'alias' => $relation->getAlias(),
          'model' => $relation->getClass(), 
          'foreign_id' => $relation->getForeign()
      );
    }
//    var_dump($relations);
//    var_dump($columnRelations);
//    die;
    

    $columns = Doctrine::getTable($this->modelName)->getColumns();
    
    if (isset($relations['Translation']))
    {
      $translationColumns = Doctrine::getTable($this->modelName . 'Translation')->getColumns();
      unset($translationColumns['id']);
      unset($translationColumns['lang']);
      
      $columns += $translationColumns;
    }

//    var_dump($columns);
//    die;    
    
    // loop through table (Doctrine) columns
    foreach ($columns as $columnName => $column)
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
      $columnConfig['is_in_list']   = true;
      $columnConfig['validatorOptions']['required'] = false; //must be set, as default = true
      
      if (isset($this->system_column_names_humanized[$columnName])) 
      {
        $columnConfig['label'] = __($this->system_column_names_humanized[$columnName], null, 'common');
      }
      
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
          
        case 'boolean':
          $columnConfig['metaWidget'] = 'ullMetaWidgetCheckbox';
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
      
      // mark translated fields
      if (isset($translationColumns[$columnName]))
      {
        $columnConfig['translation'] = true;
      }
      
      // remove certain columns from the list per default
      if (in_array($columnName, $this->columnsNotShownInList))
      {
        $columnConfig['is_in_list'] = false;
      }
      
      // parse UllColumnConfigData table
      $columnConfig = UllColumnConfigTable::addColumnConfigArray($columnConfig, $this->modelName, $columnName);
      
      
//      var_dump($column);
      
      // TODO: more defaults (humanized default label names, ...)
      
      // TODO: more Doctrine column config (widget by column type, primary keys, unique, notnull, ...)

      // TODO: handle default "ullRecord" columns like created_by, Namespace etc...
      
      $this->columnsConfig[$columnName] = $columnConfig;
    }
    
    $this->removeBlacklistColumns();
    
    $this->setReadOnlyColumns();
      
    $this->sortColumns();
    
    
//    var_dump($this->columnsConfig);
//    die;    
  }
  

  /**
   * remove unwanted columns
   *
   */
  protected function removeBlacklistColumns()
  {
    foreach ($this->columnsBlacklist as $column)
    {
      unset($this->columnsConfig[$column]);
    }
  }
  
  /**
   * set columns which are always read only
   *
   */
  protected function setReadOnlyColumns()
  {
    foreach($this->columnsReadOnly as $column)
    {
      $this->columnsConfig[$column]['access'] = 'r';
    }
  }
  
  /**
   * do some default sorting of the column order
   *
   */
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