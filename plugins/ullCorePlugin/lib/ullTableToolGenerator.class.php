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
  
  protected $historyGenerators = array();
  protected $futureGenerators = array();
  protected $isVersionable = false;
  protected $hasVersions = false;
  protected $isHistoryBuilt = false;
  protected $isFutureBuilt = false;
  protected $enableFutureVersions = false;
  
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
    
    $this->isVersionable = Doctrine::getTable($this->modelName)->hasTemplate('Doctrine_Template_SuperVersionable');
    if ($this->isVersionable())
    {
      $this->enableFutureVersions = Doctrine::getTable($this->modelName)
       ->getTemplate('Doctrine_Template_SuperVersionable')
       ->getPlugin()->getOption('enableFutureVersions');
    }
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
  public function getIdentifierUrlParamsAsArray($row)
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
      $array[$identifier] = $this->rows[$row]->$identifier;
    }
    
    return $array;
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
  public function getIdentifierAsArray()
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
      // take the first relation for each column and don't overwrite them lateron
      if (!isset($columnRelations[$relation->getLocal()]))
      {
        $columnRelations[$relation->getLocal()] = array(
          'model' => $relation->getClass(), 
          'foreign_id' => $relation->getForeign()
        );
      }
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
      $columnConfig['unique']       = false;
      
      if (isset($this->system_column_names_humanized[$columnName])) 
      {
        $columnConfig['label'] = $this->system_column_names_humanized[$columnName];
      }
      
      switch ($column['type'])
      {
        case 'string':
          if ($column['length'] > 255)
          { 
            $columnConfig['metaWidget'] = 'ullMetaWidgetTextarea';
          }
          else
          {
            $columnConfig['metaWidget'] = 'ullMetaWidgetString'; 
            $columnConfig['widgetAttributes']['maxlength'] = $column['length'];
            $columnConfig['validatorOptions']['max_length'] = $column['length'];
          }
          break;

        case 'clob':
          $columnConfig['metaWidget'] = 'ullMetaWidgetTextarea';
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
      
      if (isset($column['unique']))
      {
        $columnConfig['unique'] = true;
      }      
      
      if (isset($column['primary']))
      {
        $columnConfig['access'] = 'r';
        $columnConfig['unique'] = true;
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
      
      // try to translate label 
      $columnConfig['label'] = __($columnConfig['label'], null, 'common');       
      
      $this->columnsConfig[$columnName] = $columnConfig;
    }
    
    
    $this->removeBlacklistColumns();
    
    $this->setReadOnlyColumns();
      
    $this->sortColumns();

    if ($this->isVersionable() && $this->enableFutureVersions == true)
    {
      $columnConfig = array(
        'widgetOptions'      => array(),
        'widgetAttributes'   => array(),
        'validatorOptions'   => array(),
      );

      $columnConfig['label']        = 'Scheduled update date';
      $columnConfig['metaWidget']   = 'ullMetaWidgetDate';
      $columnConfig['access']       = $this->defaultAccess;
      $columnConfig['is_in_list']   = false;
      $columnConfig['validatorOptions']['required'] = false; //must be set, as default = true
      $tomorrow = mktime(0, 0, 0, date("m"), date("d")+1, date("y"));
      $columnConfig['validatorOptions']['min'] = $tomorrow;
      $columnConfig['validatorOptions']['date_format_range_error'] = ull_date_pattern(false, true); //no time display
      $this->columnsConfig['scheduled_update_date'] = $columnConfig;
    }

//        var_dump($this->columnsConfig);
//        die;
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
  
  /**
   * Is the object this generator represents SuperVersionable?
   * 
   * @return boolean
   */
  public function isVersionable()
  {
    return $this->isVersionable;
  }
  
  /**
   * Are there generated versions?
   * 
   * @return boolean
   */
  public function hasGeneratedVersions()
  {
    return $this->isHistoryBuilt;
  }
  
  /**
   * Are there generated future versions?
   * 
   * @return boolean
   */
  public function hasFutureVersions()
  {
    return $this->isFutureBuilt;
  }
  
  /**
   * Internal function, checks the history requirements
   * 
   * @return void
   */
  private function checkHistoryRequirements()
  {
    if (!$this->isVersionable)
    {
      throw new RuntimeException('This model is not auditing versions.');
    }

    if (!$this->isBuilt)
    {
      throw new RuntimeException('You have to call buildForm() first.');
    }
  }
  
  /**
   * Gets the built history generators
   * 
   * @return the history generators
   */
  public function getHistoryGenerators()
  {
    $this->checkHistoryRequirements();

    if (!$this->isHistoryBuilt)
    {
      throw new RuntimeException('You have to call buildHistoryGenerators() first.');
    }

    return $this->historyGenerators;
  }
  
  /**
   * Gets the future generators
   * 
   * @return the future generators
   */
  public function getFutureGenerators()
  {
    $this->getHistoryGenerators(); //requirements check

    return $this->futureGenerators;
  }
  
  /**
   * Builds the history (and future) generators.
   * 
   * This retrieves past and future versions for a row and
   * constructs a history/future generator for each pair.
   * 
   * The generators can then be displayed by the view.
   * 
   * @see ullTableToolHistoryGenerator
   * 
   * @return void
   */
  public function buildHistoryGenerators()
  {
    $this->checkHistoryRequirements();

    if (count($this->rows) != 1)
    {
      throw new RuntimeException('Not implemented.');
    }

    $row = $this->rows[0];
    if (!$row->exists())
    {
      //don't throw an exception, but set isHistoryBuilt to false
      //better solution would be an isCreateMode() function
      //throw new RuntimeException('Do not call buildHistoryGenerators() in create mode.');
      $this->isHistoryBuilt = false;
      return;
    }

    $maxVersion = $row->getAuditLog()->getMaxVersionNumber($row);
    
    $rowCur = clone $row;
    $rowRev = clone $row;
    
    $this->historyGenerators = array();
    for($i = $maxVersion; $i >= 1; $i--)
    {
      if ($i > 1)
        $rowRev->revert($i - 1);
      else
        $rowRev = new $this->modelName;
        
      $this->historyGenerators[$i - 1] = new ullTableToolHistoryGenerator($this->modelName, 'r');
      $this->historyGenerators[$i - 1]->buildHistoryForm($rowCur, $rowRev);

      $rowCur = clone $rowRev;
    }

    $futureVersions = $row->getFutureVersions();

    if (count($futureVersions) > 0) {
      $q = Doctrine::getTable($this->modelName)->createQuery('c')
            ->where('c.id = ?', $futureVersions[0]->id);
      $rowRev = $q->fetchOne();
      
      for($i = 0; $i < count($futureVersions); $i++)
      {
        $rowRev->revert($futureVersions[$i]->reference_version);
        
        $this->futureGenerators[$i] = new ullTableToolHistoryGenerator($this->modelName, 'r');
        $this->futureGenerators[$i]->buildHistoryForm($futureVersions[$i], $rowRev);
      }
      $this->isFutureBuilt = true;
    }
    
    $this->isHistoryBuilt = true;
  }
}