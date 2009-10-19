<?php

class ullTableToolGenerator extends ullGenerator
{
  protected
    // TODO: check if some of these properties belong to ullGenerator
    $historyGenerators = array(),
    $futureGenerators = array(),
    $isVersionable = false,
    $hasVersions = false,
    $isHistoryBuilt = false,
    $isFutureBuilt = false,
    $enableFutureVersions = false,
    /**
     * A list of columns in the format Relation.fieldName
     */
    $columns = array()
  ;
  
  /**
   * Constructor
   *
   * @param string $modelName
   * @param string $defaultAccess can be "r" or "w" for read or write
   */
  public function __construct($modelName, $defaultAccess = null, $requestAction = null, $columns = array())
  {
    $this->handleDefaultAccessAndRequestAction($defaultAccess, $requestAction);
    
    $this->formClass = 'ullTableToolForm';
    $this->modelName = $modelName;
    
    $this->validateConstructorParams($this->modelName);
    $this->checkForVersionableBehaviour($this->modelName);

    // has to be called before setting the columns once 
    $this->buildTableConfig();
    
    // handle new column param for list action
    if ($columns)
    {
      $this->columns = $columns;
    }
    else
    {
      if ($this->isListAction())
      {
        $this->columns = $this->getTableConfig()->getListColumns();
      }
      // deactivated at the moment until we further investigate the relation handling
      //  for the edit actions
//      else
//      {
//        $this->columns = $this->getTableConfig()->getEditColumns();
//      } 
    }
    
    $this->buildColumnsConfig();
  }
  
  
  /**
   * Validate constructor parameters
   * 
   * * Model is mandatory
   * * Model must exist
   * 
   * @return none
   */
  protected function validateConstructorParams()
  {
    if ($this->modelName === null)
    {
      throw new InvalidArgumentException('A model must be supplied');
    }

    if (!class_exists($this->modelName))
    {
      throw new InvalidArgumentException('Invalid model: ' . $this->modelName);
    }    
  }
  
  
  /**
   * Handle versionable behaviour if enabled for the current model
   * 
   * @return none
   */
  protected function checkForVersionableBehaviour()
  {
    $this->isVersionable = Doctrine::getTable($this->modelName)->hasTemplate('Doctrine_Template_SuperVersionable');
    
    if ($this->isVersionable())
    {
      $this->enableFutureVersions = Doctrine::getTable($this->modelName)
          ->getTemplate('Doctrine_Template_SuperVersionable')
          ->getPlugin()
          ->getOption('enableFutureVersions');
    }    
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
   * Get the identifier value of a row
   * 
   * Supports only tables with a single primary key
   * 
   * Usage example: UllUser: inject the id into the photo widget to create edit link  
   * 
   * @param $row
   * @return unknown_type
   */
  public function getIdentifierValue($row = 0)
  {
    $identifierColumns = $this->getIdentifierAsArray();
    if (count($identifierColumns) > 1)
    {
      throw new Exception('Composite identifiers are not supported by getIdentifierValue()');
    }
    $identifierColumn = $identifierColumns[0];
    
    return $this->rows[$row]->$identifierColumn;
  }

  
  /**
   * builds the table config
   *
   */
  protected function buildTableConfig()
  {
    $this->tableConfig = ullTableConfiguration::buildFor($this->modelName);
  }

  /**
   * builds the column config
   *
   */
  protected function buildColumnsConfig()
  {
    $this->columnsConfig = ullColumnConfigCollection::buildFor($this->modelName, $this->defaultAccess, $this->requestAction);
    
    $this->handleRelationColumns();
    
    if ($this->isVersionable() && $this->enableFutureVersions == true)
    {
      $tomorrow = mktime(0, 0, 0, date("m"), date("d")+1, date("y"));

      if ($this->isCreateOrEditAction())
      {
        $this->columnsConfig->create('scheduled_update_date')
          ->setLabel('Scheduled update date')
          ->setMetaWidgetClassName('ullMetaWidgetDate')
          ->setValidatorOption('required', false) //must be set, as default = true
          ->setValidatorOption('min', $tomorrow)
          ->setValidatorOption('date_format_range_error', ull_date_pattern(false, true)); //no time display
      }
    }

//    var_dump($this->columnsConfig->getActive());
//    die;
  }
  
  /**
   * New relation column handling
   * 
   * Get the columnConfigurations for the related columns
   * and store the relation information in the columnConfiguration as customAttributes
   * 
   * Also do it only for the selected columns
   * 
   * @return unknown_type
   */
  protected function handleRelationColumns()
  {
    // use the new relation handling if we have a list of columns
    if ($this->columns)
    {
      $this->columnsConfig->disableAllExcept(array());
      
      foreach ($this->columns as $column)
      {
        // native columns
        if (!ullGeneratorTools::hasRelations($column))
        {
          $this->columnsConfig[$column]->setAccess($this->getDefaultAccess());
        }
        else
        {
          //resolve relation to model
          $relations = ullGeneratorTools::relationStringToArray($column);
          $field = array_pop($relations);
          
          $finalModel = ullGeneratorTools::getFinalModelFromRelations($this->modelName, $relations);
          
//          var_dump($relations);
//          var_dump($field);
//          var_dump($finalModel);          
          
          $relationColumnsConfig = ullColumnConfigCollection::buildFor($finalModel);
          
//          var_dump($relationColumnsConfig);
          
          $this->columnsConfig[$column] = $relationColumnsConfig[$field];
          $this->columnsConfig[$column]
            ->setColumnName($column)
            ->setAccess($this->getDefaultAccess())
            ->setCustomAttribute('field', $field)
            ->setCustomAttribute('relations', $relations)
            ->setLabel(ullGeneratorTools::buildRelationsLabel($this->modelName, $relations))
          ;          
        }
      }
      
      $this->columnsConfig->order($this->columns);
    } 
    
//    var_dump($this->columnsConfig->getActive());
//    die;    
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
  protected function checkHistoryRequirements()
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
      $this->historyGenerators[$i - 1]->buildHistoryForm($rowCur, $rowRev, $this->enableFutureVersions);

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
	        $this->futureGenerators[$i]->buildHistoryForm($futureVersions[$i], $rowRev, $this->enableFutureVersions);
	      }
	      $this->isFutureBuilt = true;
	    }
    }

    $this->isHistoryBuilt = true;
  }

  
  /**
   * Returns the enabled status of future version functionality.
   * @return boolean true or false
   */
  public function getEnableFutureVersions()
  {
    return $this->enableFutureVersions;
  }
  
  
  /**
   * Create a ullQuery for the current model
   * 
   * @return ullQuery
   */
  public function createQuery()
  {
    $q = new ullQuery($this->modelName);
    $q->addSelect(array_keys($this->getActiveColumns()));
    
    return $q; 
  }

}