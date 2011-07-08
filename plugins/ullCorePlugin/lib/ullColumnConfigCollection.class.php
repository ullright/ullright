<?php 

/**
 * The ullColumnConfigCollection class represents on one hand an "array object"
 * as a collection of ullColumnConfigs, on the other hand it builds the column
 * configs for a given model and allows customization of the columnConfigs
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class ullColumnConfigCollection extends ullGeneratorBase implements ArrayAccess, Countable, IteratorAggregate
{
  protected 
    $collection = array(),
    /* Format similiar to Doctrine::getTable('MyModel')->getColumns()
     * 
     *  array(
     *    "column_name" =>
     *      array(
     *        "type"          => "integer"
     *        "length"        => 20
     *        "autoincrement" => true
     *        "primary"       => true
     *      )
     *  )
     */
    $tableConfigCache,     
    $columns = array(),
    $relations = array(),
    $modelName,
    $defaultAccess,     
    $requestAction,
    
    $blacklist = array(
          'namespace',
          'version',
    ),
    // columns which should be displayed at last position
    $orderBottom = array(
          'creator_user_id',
          'created_at',
          'updator_user_id',
          'updated_at',
    ),
    $showOnlyInEditModeAndReadOnly = array(
          'id',
          'slug',
          'creator_user_id',
          'created_at',
          'updator_user_id',
          'updated_at',
    )
  ;
  
  /**
   * Constructor
   * 
   * @param $modelName string Doctrine model name e.g. 'TestTable'
   * @param $requestAction string symfony controller requestAction. e.g. 'list', 'create', 'edit'
   * @param $defaultAccess string 'r' or 'w'
   * @return none
   */
  public function __construct($modelName, $defaultAccess = null, $requestAction = null)
  {
    $this->modelName = $modelName;
    
    parent::__construct($defaultAccess, $requestAction);
  }
  
  /**
   * Default static method to get a ullColumnConfigCollection for a model
   * 
   * It's possible to customize per model by providing a myModelColumnConfigCollection class
   * 
   * @param $modelName string Doctrine model name e.g. 'TestTable'
   * @param $defaultAccess string 'r' or 'w'
   * @param $requestAction string symfony controller requestAction. one of 'list', 'create', 'edit'
   * @return ullColumnConfigurationCollection
   */
  public static function buildFor($modelName, $defaultAccess = null, $requestAction = null)
  {
    $className = $modelName . 'ColumnConfigCollection';
    if (class_exists($className))
    {
      $c = new $className($modelName, $defaultAccess, $requestAction);
    }
    else
    {
      $c = new self($modelName, $defaultAccess, $requestAction);
    }
    $c->buildCollection();
    
    return $c;
  }
  
  /**
   * Returns the columnConfigs for the current model.
   * 
   * It applies several levels of configuration:
   *    1) Common settings
   *        - Common labels + translations
   *        - disabled
   *        - isInList
   *    2) Doctrine schema settings
   *        - not_null
   *        - unique
   *        - type (metaWidget)
   *        - length
   *        - relations
   *    3) Custom settings by overwriting applyCustomColumnConfigSettings()
   * 
   * @return array
   */
  protected function buildCollection()
  {
    $this->buildColumns();    
    
    $this->createColumnConfigs();
    
    $this->applyCommonSettings();
    
    $this->applyDoctrineSettings();
    
    $this->applyCustomSettings();
  }

  /**
   * Build a list of columns
   * 
   * Default are the model columns
   * 
   * There can be additional columns to the ones of the model
   * Example: translations, virtual columns of ullFlow, ...
   * 
   * This method is intended to be overwritten by child classes if necessary
   * 
   * @return none
   */  
  protected function buildColumns()
  {
    $modelTable = Doctrine::getTable($this->modelName);
    
    $this->columns = $modelTable->getColumns();
    
    //handle translated fields
    if ($modelTable->hasRelation('Translation'))
    {
      $translationColumns = Doctrine::getTable($this->modelName . 'Translation')->getColumns();
      unset($translationColumns['id']);
      unset($translationColumns['lang']);

      foreach ($translationColumns as $translationColumnName => $translationColumn)
      {
        $translationColumn['translated'] = true;
        $this->columns[$translationColumnName] = $translationColumn;
      }
    }
  }  
  
  /**
   * Fill collection array with empty ullColumnConfigurations for each
   * model column and set translation flag if necessary
   * 
   * @return none
   */
  protected function createColumnConfigs()
  {
    foreach ($this->columns as $columnName => $column)
    {
      $this->collection[$columnName] = new ullColumnConfiguration;

      if (isset($column['translated']) && $column['translated'] === true)
      {
        $this->collection[$columnName]->setTranslated(true);
      }
    }
  }  
  
  
  /**
   * Apply common settings
   * 
   * @return none
   */
  protected function applyCommonSettings()
  {
    foreach ($this->collection as $columnName => $columnConfig)
    {
      $columnConfig
        ->setColumnName($columnName)
        ->setModelName($this->modelName)
        ->setAccess($this->defaultAccess)
        ->setLabel(ullHumanizer::humanizeAndTranslateColumnName($columnName))
      ;
    }
    
    if (isset($this['slug']))
    {
       $this['slug']->setHelp(__('Name of the entry in the address bar (URL)', null, 'common'));
    }

    $this->showOnlyInEditModeAndReadOnly();
    
    $this->blacklist();
    
    $this->orderBottom($this->orderBottom);    
  }
  
  
  /**
   * Remove unwanted columns
   *
   */
  protected function blacklist(array $blacklist = null)
  {
    if ($blacklist === null)
    {
      $blacklist = $this->blacklist;
    }
    
    foreach ($blacklist as $column)
    {
      if (isset($this->collection[$column]))
      {
        $this->collection[$column]->disable();
      }
    }
  }
  
  
  /**
   * Remove columns except in edit mode -> display readonly
   * 
   * @return none
   */
  protected function showOnlyInEditModeAndReadOnly()
  {
    foreach ($this->showOnlyInEditModeAndReadOnly as $column)
    {
      if ($this->isEditAction())
      {
        if (isset($this->collection[$column]))
        {
          // slug is editable for master admins
          if (!($column == 'slug' && UllUserTable::hasGroup('MasterAdmins')))
          {
            $this->collection[$column]->setAccess('r');
          }
        }
      }
      else
      {
        if (isset($this->collection[$column]))
        {
          $this->collection[$column]->disable();
        }    
      } 
    }
    
    $this->markAsAdvancedFields($this->showOnlyInEditModeAndReadOnly);
  }
  
  
  /**
   * Apply Doctrine settings
   * 
   * @return none
   */
  protected function applyDoctrineSettings()
  {
    foreach ($this->collection as $columnName => $columnConfig)
    {
      $this->applyDoctrineColumnSettings($columnName, $columnConfig);
      $this->applyDoctrineRelationSettings($columnName, $columnConfig);
    }

    $this->orderTranslatedColumns();
  }    

  
  /**
   * Configure columnConfig according to the settings of the doctrine table column
   * 
   * Examples: type (integer, string, ...), notnull, unique...
   * @param $columnName
   * @param $columnConfig
   * @return none
   */
  protected function applyDoctrineColumnSettings($columnName, $columnConfig)
  {
    $column = $this->columns[$columnName];
    
    $type = $column['type'];
    $length = $column['length'];
    
    $columnConfig->setMetaWidgetClassName(ullMetaWidget::getMetaWidgetClassName($type));
    
    if ($type == 'string')
    {
      if ($length > 255)
      {
        $columnConfig->setMetaWidgetClassName('ullMetaWidgetTextarea');
      }
      else
      {
        $columnConfig
          ->setWidgetAttribute('maxlength', $length)
          ->setValidatorOption('max_length', $length)
        ;
      }
    }
    elseif ('date' == $type)
    {
      $columnConfig->setMetaWidgetClassName('ullMetaWidgetDate');
    }
    elseif ('time' == $type)
    {
      $columnConfig->setMetaWidgetClassName('ullMetaWidgetTime');
    }            
    elseif (in_array($type, array('timestamp', 'datetime')))
    {
      $columnConfig->setMetaWidgetClassName('ullMetaWidgetDateTime');
    }
    
    if (in_array($type, array('integer', 'float')))
    {
      // Do not build sums for id columns (match "id" at the end)
      if (!preg_match('/id$/', $columnName))
      {
        $columnConfig->setCalculateSum(true);
      }
    }
    
    if (isset($column['notnull']))
    {
      $columnConfig->setValidatorOption('required', true);
    }
    
    if (isset($column['unique']))
    {
      $columnConfig->setUnique(true);
    }

    if (isset($column['primary']))
    {
      $columnConfig
        ->setUnique(true)
        ->setValidatorOption('required', true)
      ;      
      
      // don't re-enable inactive columns
      if ($columnConfig->isActive())
      {
        $columnConfig->setAccess('r');
      }
    }    
      
  }


  /**
   * Set relation information in the columnConfig per column
   *
   * first we check for regular 'forward' relations,
   * ??? if there isn't one we try the 'backward' relations.
   * ??? example: from user to his groups via entitygroup.
   * 
   * @param $columnName
   * @param $columnConfig
   * @return unknown_type
   */      
  protected function applyDoctrineRelationSettings($columnName, $columnConfig)
  {
    // don't resolve relations for primary keys
    if (isset($this->columns[$columnName]['primary'])) // || $this->columnName != 'id'
    {
      return null;
    }

    $relations = $this->getRelations();
    
    if (isset($relations[$columnName]))
    {
      $relation = $relations[$columnName];
      
      $columnConfig->setRelation($relation);
      
      switch($relation['model'])
      {
        case 'UllUser': 
          $columnConfig
            ->setMetaWidgetClassName('ullMetaWidgetUllEntity')
            ->setOption('entity_classes', array('UllUser'))
          ;
          break;
        
        case 'UllGroup': 
          $columnConfig
            ->setMetaWidgetClassName('ullMetaWidgetUllEntity')
            ->setOption('entity_classes', array('UllGroup'))
          ;
          break;
        
        case 'UllEntity': 
          $columnConfig->setMetaWidgetClassName('ullMetaWidgetUllEntity');
          break;
          
        default:
          $columnConfig->setMetaWidgetClassName('ullMetaWidgetForeignKey');
      }
    }
//    else 
//    {
//      if ($columnRelationsForeign != null)
//      {
//        if (isset($columnRelationsForeign[$this->columnName]))
//        {
//          $this->metaWidgetClassName = 'ullMetaWidgetForeignKey';
//          $this->relation = $columnRelationsForeign[$this->columnName];
//          
//          //resolve second level relations for many to many relationships
//          $relations = Doctrine::getTable($columnRelationsForeign[$this->columnName]['model'])->getRelations();
//          foreach ($relations as $relation)
//          {
//            if ($relation->getLocal() == $this->columnName)
//            {
//              //var_dump('new model: ' . $relation->getClass());
//               $this->relation['model'] = $relation->getClass();
//              
//              break;
//            }
//          }
//        }
//      }
//    }
  }  
  

  /**
   * Build relation information for columnConfigs
   */      
  protected function getRelations()
  {
    if (!$this->relations)
    {
      foreach(Doctrine::getTable($this->modelName)->getRelations() as $alias => $relation)
      {
        // take the first relation for each column and don't overwrite them later on
        if (!isset($this->relations[$relation->getLocal()]))
        {
          $this->relations[$relation->getLocal()] = array(
            'alias'       => $alias,
            'model'       => $relation->getClass(), 
            'foreign_id'  => $relation->getForeign()
          );
        }
      }
    }
    
    return $this->relations;
  }  
  
  
  /**
   * Return relation info by an Alias name
   * 
   * @param string $alias
   * @return array
   */
  protected function getRelationByAlias($alias)
  {
    foreach (Doctrine::getTable($this->modelName)->getRelations() as $currentAlias => $relation)
    {
      if ($currentAlias == $alias)
      {
        return $relation;
      }
    }
  }
  
  
  /**
   * Move translated columns to the top of the list (after "id")
   * Reason: the translated columns are often descriptive, and are expected to be on top
   *
   * @return none
   */
  protected function orderTranslatedColumns()
  {
    $order = array_merge(array('id'), $this->getTranslatedColumns());
    
    $this->order($order);
  }
  
  
  /**
   * Returns an array with the names of translated columns
   * 
   * @return array
   */
  public function getTranslatedColumns()
  {
    $translatedColumns = array();
    
    foreach ($this->collection as $key => $columnConfig)
    {
      if ($columnConfig->getTranslated())
      {
        $translatedColumns[] = $key;
      }
    } 
    
    return $translatedColumns;
  }
  
  
  /** 
   * Get a list of active columnConfigurations
   * 
   * @return array
   */
  public function getActiveColumns()
  {
    $activeColumns = array();
    
    foreach($this->collection as $key => $columnConfig)
    {
      if ($columnConfig->isActive())
      {
        $activeColumns[$key] = $columnConfig;
      }
    }
    
    return $activeColumns;
  }
  
  
  /**
   * Get a list of columnConfigurations for database fields
   * 
   * @return array of column configs
   */
  public function getDatabaseColumns()
  {
    $columns = $this->getActiveColumns();

    foreach ($columns as $columnKey => $column)
    {
      if ($column->getIsArtificial())
      {
        unset($columns[$columnKey]);
      }
    }
    
    return $columns;
  }  
  
  
  /**
   * Get a list of active columnConfigurations that are marked
   * to be rendered automatically 
   * 
   * @return array
   */
  public function getAutoRenderedColumns()
  {
    $columns = $this->getActiveColumns();

    foreach ($columns as $columnKey => $column)
    {
      if (!$column->getAutoRender())
      {
        unset($columns[$columnKey]);
      }
    }
    
    return $columns;
  }     
  
  /**
   * Get an array of columnConfigurations that are marked
   * as unsortable 
   * 
   * @return array of column configs where sortable == false
   */
  public function getUnsortableColumns()
  {
    $unsortableColumns = array();
    foreach($this->collection as $columnName => $columnConfig)
    {
      if (!$columnConfig->getIsSortable())
      {
        $unsortableColumns[$columnName] = $columnConfig;
      }
    }
    
    return $unsortableColumns;
  }
  
  /** 
   * Alias for getActiveColumns()
   * 
   * @return array
   */
  public function getActive()
  {
    return $this->getActiveColumns();
  }
  
  
  /**
   * Empty method to be overwritten by child classes
   * 
   * @return unknown_type
   */
  protected function applyCustomSettings()
  {
   
  }   
  
  
  /**
   * Returns the model name
   * 
   * @return string
   */
  public function getModelName()
  {
    return $this->modelName;
  }
  
  
  /**
   * Returns the keys of the collection as array similar to array_keys()
   * 
   * @return array
   */
  public function getKeys()
  {
    return array_keys($this->collection);
  }
  
  
  /**
   * Orders the collection using the order of the given array of field names
   * Format:
   * 
   * array(
   *  'field_1',
   *  'field_2',
   *  ...
   * );
   * 
   * Also supports the subdivision into sections:
   * Format:
   * 
   * array(
   *   'section_1' => array(
   *     'field_1',
   *     'field_2',
   *    ),
   *    'section_2' => array(
   *      'field_3',
   *    ),
   *    // also mixed without given section:
   *    field_4, 
   *  );
   *
   * When giving no section in the array, the section is cleared.
   * 
   * From ullCoreTools::orderArrayByArray:
   * Orders the top level of an associative array by a given array
   * Keys which are not defined by $order remain unchanged at the end of return array
   * 
   * @param $array
   * @return none
   */
  public function order($array)
  {
    $plainArray = array();
    
    foreach ($array as $section => $sectionOrField)
    {
      if (is_array($sectionOrField))
      {
        foreach($sectionOrField as $field)
        {
          $this[$field]->setSection($section);
          $plainArray[] = $field;
        }
      }
      else
      {
        $this->collection[$sectionOrField]->setSection(null);
        $plainArray[] = $sectionOrField;
      }
    }
    
    $this->collection = ullCoreTools::orderArrayByArray($this->collection, $plainArray);
  }
  
  
  /**
   * Takes an array of keys and orders the bottom of the collection accordingly
   * See ullColumnConfigCollectionTest for example.
   * 
   * @param $array array of keys
   * @return none
   */
  public function orderBottom($array)
  {
    $bottom = array();
    foreach ($array as $key)
    {
      $bottom[$key] = $this->collection[$key];
      unset($this->collection[$key]);
    }

    $this->collection = array_merge($this->collection, $bottom);      
  }

  /**
   * Creates a new ColumnConfiguration
   * 
   * @param $columnName
   * @return ullColumnConfiguration
   */
  public function create($columnName)
  {
    $this->collection[$columnName] = new UllColumnConfiguration;    
    $this->collection[$columnName]
      ->setColumnName($columnName)
      ->setAccess($this->defaultAccess)
    ;
    
    return $this->collection[$columnName];
  }
  
  /**
   * Disables the given columns
   * 
   * @param $array array of columnNames
   * @param $withoutErrors boolean specifying if invalid column names
   * (i.e. ones that are in $array but not in this column configuration
   * collection) should result in errors or be silently discarded
   * @return none
   */
  public function disable($array, $withoutErrors = false)
  {
    if (!is_array($array))
    {
      $array = array($array);
    }
    
    foreach ($array as $columnName)
    {
      if (!$withoutErrors || isset($this->collection[$columnName]))
      {
        $this->collection[$columnName]->setAccess(null);
      }    
    }
  }
  
  
  /**
   * Disables all columns except those given
   * 
   * Only for columns which are auto-rendered
   * 
   * @param $array of columnNames
   * @return none
   */
  public function disableAllExcept(array $array)
  {
    foreach ($this->collection as $key => $columnConfig)
    {
      if (array_search($key, $array) === false)
      {
        if ($columnConfig->getAutoRender() == true)
        {
          $columnConfig->setAccess(null);
        }
      }
    }
  }

  /**
   * Completely removes the given columns from this
   * column config collection
   * 
   * @param $array array of columnNames
   * @param $ignoreErrors boolean specifying if invalid column names
   *   (i.e. ones that are in $array but not in this column configuration
   *   collection) should result in errors or be silently discarded
   * @return none
   */
  public function remove($array, $ignoreErrors = false)
  {
    if (!is_array($array))
    {
      $array = array($array);
    }
    
    foreach ($array as $columnName)
    {
      if (!$ignoreErrors || isset($this->collection[$columnName]))
      {
        unset($this->collection[$columnName]);
      }    
    }
  }
  
  /**
   * Enables the given columns
   * 
   * @param $array array of columnNames
   * @return none
   */
  public function enable(array $array)
  {
    foreach ($array as $columnName)
    {
      $this->collection[$columnName]->setAccess($this->defaultAccess);     
    }
  }
  
  
  /**
   * Get the whole collection array
   * 
   * @return array
   */  
  public function getCollection()
  {
    return $this->collection;
  }
  
  
  public function setIsRequired(array $array)
  {
    foreach ($array as $field)
    {
      $this->collection[$field]->setIsRequired(true);  
    }  
  }
  
  
  // ArrayAccess methods
  
  public function offsetExists($offset)
  {
    if (array_key_exists($offset, $this->collection))
    {
      return true;
    }    
    
    return false;
  }
  
  public function offsetGet($offset)
  {
    if ($this->offsetExists($offset))
    {
      return $this->collection[$offset];
    }
    else
    {
      throw new InvalidArgumentException('Invalid offset given: ' . $offset);
    } 
  }
  
  public function offsetSet($offset, $value)
  {
    if ($value instanceof ullColumnConfiguration)
    {
      $this->collection[$offset] = $value;
    }
    else
    {
      throw new InvalidArgumentException('Given value must be a ullColumnConfiguration object');
    }
  }
  
  public function offsetUnset($offset)
  {
    if ($this->offsetExists($offset))
    {
      unset($this->collection[$offset]);
    }
    else
    {
      throw new InvalidArgumentException('Invalid offset given: ' . $offset);
    } 
  }
  
  public function getFirst()
  {
    return reset($this->collection);
  }
  
  public function getLast()
  {
    return end($this->collection);
  }  
  
  // Iterator methods
  
  public function getIterator()
  {
    return new ArrayIterator($this->collection);
  }  
  
  // Countable methods
  
  public function count()
  {
    return count($this->collection);
  }

  /**
   * Add a field for a many to many relation
   *  
   * @param string $relationAlias
   * @param string $model optional model name override
   *                      This is useful e.g. for UllEntity / UllUser issues etc
   *               $hideInactive if set to true and the relation model has a field named
   *                      'is_active', a where clause 'is_active = true' is added to the query
   */
  public function useManyToManyRelation($relationAlias, $model = null, $hideInactive = true)
  {
    $relation = $this->getRelationByAlias($relationAlias);
    $toStringColumn = ullTableConfiguration::buildFor($relation->getClass())->getToStringColumn();
    
    if (!$model)
    {
      $model = $relation->getClass();
    }
    
    $q = new Doctrine_Query();
    $q
      ->select($toStringColumn)
      ->from($model)
      ->orderBy($toStringColumn)
      //better performance
      ->setHydrationMode(Doctrine::HYDRATE_ARRAY);
    ;
    
    if ($hideInactive && Doctrine::getTable($model)->hasField('is_active'))
    {
      $q->where('is_active = true');
    }
    
    $this->create($relationAlias)
      ->setMetaWidgetClassName('ullMetaWidgetManyToMany')
      //set model (it's a required option)
      ->setWidgetOption('model', $model)
      ->setWidgetOption('query', $q)
      //see ullWidgetManyToManyWrite class doc for why we set this
      ->setWidgetOption('key_method', 'id')
      ->setWidgetOption('method', $toStringColumn)
      ->setWidgetOption('owner_model', $this->getModelName())
      ->setWidgetOption('owner_relation_name', $relationAlias)
      ->setValidatorOption('model', $model)
      ->setValidatorOption('query', $q)
      ->setIsSortable(false) //at the moment, we do not have support for this
    ;     
  }
  
  
  /**
   * Lazy load the according table configuration and returns it
   * 
   * @return: ullTableConfiguration
   */
  public function getTableConfig()
  {
    if (!$this->tableConfigCache)
    {
      $this->tableConfigCache = ullTableConfiguration::buildFor($this->modelName);
    }
    
    return $this->tableConfigCache;
  }

  
  /**
   * Mark a field as advanced form field.
   * 
   * This means it is hidden per default and replaced with a link to 
   * "advanced options" by js
   * 
   * @param array $array list of field names
   */
  public function markAsAdvancedFields($array)
  {
    foreach ($array as $field)
    {
      if (isset($this[$field]))
      {
        $this[$field]->markAsAdvancedField();  
      }
    }  
    
    return $this;
  }
  
  /**
   * Debugging output
   * 
   * @return multitype:
   */
  public function debug()
  {
    $return = array();
    
    foreach ($this as $key => $columnConfig)
    {
      $return[$key] = ullCoreTools::debugArrayWithDoctrineRecords($columnConfig);
    }
    
    return $return;
  }
}