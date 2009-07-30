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
  public function __construct($modelName = null, $defaultAccess = null, $requestAction = null)
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
          ->getPlugin()
          ->getOption('enableFutureVersions');
    }
    
    parent::__construct($defaultAccess, $requestAction);
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
    $columnRelations = ullGeneratorHelper::resolveDoctrineRelations($this->modelName);
    
//    var_dump($columnRelations);

    $columns = Doctrine::getTable($this->modelName)->getColumns();

    if (Doctrine::getTable($this->modelName)->hasRelation('Translation'))
    {
      $translationColumns = Doctrine::getTable($this->modelName . 'Translation')->getColumns();
      unset($translationColumns['id']);
      unset($translationColumns['lang']);

      $columns += $translationColumns;
    }

    foreach ($columns as $columnName => $column)
    {
      $columnConfig = new ullColumnConfiguration($columnName, $this->defaultAccess);
      $columnConfig->parseDoctrineColumnObject($column, $columnRelations);
      
      // mark translated fields
      if (isset($translationColumns[$columnName]))
      {
        $columnConfig->setTranslated(true);
      }

      // remove certain columns from the list per default
      if (in_array($columnName, $this->columnsNotShownInList))
      {
        $columnConfig->setIsInList(false);
      }

      // parse UllColumnConfigData table
      UllColumnConfigTable::addColumnConfigArray($columnConfig, $this->modelName, $columnName);
      // try to translate label
      $columnConfig->setLabel(__($columnConfig->getLabel(), null, 'common'));

      $this->columnsConfig[$columnName] = $columnConfig;
    }


    $this->removeBlacklistColumns();

    $this->setReadOnlyColumns();

    $this->sortColumns();

  
    if ($this->isVersionable() && $this->enableFutureVersions == true)
    {
      $columnConfig = new ullColumnConfiguration('scheduled_update_date', $this->defaultAccess);
      
      $columnConfig->setLabel('Scheduled update date');
      $columnConfig->setMetaWidgetClassName('ullMetaWidgetDate');
      $columnConfig->setIsInList(false);
      $columnConfig->setValidatorOption('required', false); //must be set, as default = true
      $tomorrow = mktime(0, 0, 0, date("m"), date("d")+1, date("y"));
      $columnConfig->setValidatorOption('min', $tomorrow);
      $columnConfig->setValidatorOption('date_format_range_error', ull_date_pattern(false, true)); //no time display
      
      $this->columnsConfig['scheduled_update_date'] = $columnConfig;
    }  
    
    //new column config (with collections)
    $ultraModernColumnConfig = ullColumnConfigCollection::buildFor($this->modelName, $this->defaultAccess, $this->requestAction);
  
    if ($this->isVersionable() && $this->enableFutureVersions == true)
    {
      $tomorrow = mktime(0, 0, 0, date("m"), date("d")+1, date("y"));

      if ($this->isCreateOrEditAction())
      {
        $ultraModernColumnConfig->create('scheduled_update_date')
          ->setLabel('Scheduled update date')
          ->setMetaWidgetClassName('ullMetaWidgetDate')
          ->setValidatorOption('required', false) //must be set, as default = true
          ->setValidatorOption('min', $tomorrow)
          ->setValidatorOption('date_format_range_error', ull_date_pattern(false, true)); //no time display
      }
    }  
    
    //flip the switch here :)
    //$this->columnsConfig = $ultraModernColumnConfig;  
  }

  /**
   * remove unwanted columns
   *
   */
  protected function removeBlacklistColumns()
  {
    foreach ($this->columnsBlacklist as $column)
    {
      if (isset($this->columnsConfig[$column]))
      {
        unset($this->columnsConfig[$column]);
      }
      //ToDo: Check why this is even necessary
      //else
      //{
      //  trigger_error("Trying to blacklist non-existing column: " . $column, E_USER_NOTICE);
      //}
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
      $this->columnsConfig[$column]->setAccess('r');
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
   * Get the model name of the data object
   *
   * @return string
   */
  public function getModelName()
  {
    return $this->modelName;
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

    if ($this->enableFutureVersions)
    {
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
    }

    $this->isHistoryBuilt = true;
  }

  public function addAllToBlacklistExcept(array $exceptColumns)
  {
    $this->columnsBlacklist = array();
    foreach ($this->getActiveColumns() as $activeColumnKey => $activeColumnValue)
    {
      if (!(in_array($activeColumnKey, $exceptColumns))) {
        $this->columnsBlacklist[] = $activeColumnKey;
      }
    }

    $this->removeBlacklistColumns();
  }
  
  /**
   * Returns the enabled status of future version functionality.
   * @return boolean true or false
   */
  public function getEnableFutureVersions()
  {
    return $this->enableFutureVersions;
  }
}