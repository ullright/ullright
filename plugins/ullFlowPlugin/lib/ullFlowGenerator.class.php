<?php

class ullFlowGenerator extends ullGenerator
{
  protected
    $formClass = 'ullFlowForm',
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
   * builds the table config
   *
   */
  protected function buildTableConfig()
  {
//    $tableConfig = Doctrine::getTable('UllTableConfig')->
//        findOneByDbTableName($this->modelName);
//        
//    if (!$tableConfig)
//    {
//      $tableConfig = new UllTableConfig;
//      $tableConfig->db_table_name = $this->modelName;
////      $tableConfig->save();
//    }
//    
//    $this->tableConfig = $tableConfig;
  }
  
  /**
   * builds the column config
   *
   */
  protected function buildColumnsConfig()
  {
    $dbColumnConfig = Doctrine::getTable('UllFlowColumnConfig')->
        findByUllFlowAppId($this->rows[0]->ull_flow_app_id);
        
    die;
    
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
      $columnConfig['show_in_list'] = true;
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
      
      // mark translated fields
      if (isset($translationColumns[$columnName]))
      {
        $columnConfig['translation'] = true;
      }
      
      // remove certain columns from the list per default
      if (in_array($columnName, $this->columnsNotShownInList))
      {
        $columnConfig['show_in_list'] = false;
      }
      
      // parse UllColumnConfigData table
      $dbColumnConfig = UllColumnConfigTable::getColumnConfigArray($this->modelName, $columnName);
      $columnConfig = array_merge($columnConfig, $dbColumnConfig);
      
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

?>