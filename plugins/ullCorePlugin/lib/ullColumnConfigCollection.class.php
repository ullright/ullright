<?php 

/**
 * The ullColumnConfigCollection class represents one one hand an "array object"
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
    $columns = array(),
    $relations = array(),
    $modelName,
    $defaultAccess,     
    $requestAction;
    
    //These defaults are private because inheriting classes
    //should use appropriate functions instead of overriding
    private
    $blacklist = array(
          'namespace',
          'type',
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
        ->setAccess($this->defaultAccess)
        ->setLabel(ullHumanizer::humanizeAndTranslateColumnName($columnName))
      ;
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
          $this->collection[$column]->setAccess('r');
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
      foreach(Doctrine::getTable($this->modelName)->getRelations() as $relation)
      {
        // take the first relation for each column and don't overwrite them later on
        if (!isset($this->relations[$relation->getLocal()]))
        {
          $this->relations[$relation->getLocal()] = array(
            'model'       => $relation->getClass(), 
            'foreign_id'  => $relation->getForeign()
          );
        }
      }
    }
    
    return $this->relations;
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
   * Orders the collection using ullCoreTools::orderArrayByArray
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
    $this->collection = ullCoreTools::orderArrayByArray($this->collection, $array);
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
   * @return none
   */
  public function disable(array $array)
  {
    foreach ($array as $columnName)
    {
      $this->collection[$columnName]->setAccess(null);     
    }
  }
  
  /**
   * Disables all columns except those given
   * @param $array of columnNames
   * @return none
   */
  public function disableAllExcept(array $array)
  {
    foreach ($this->collection as $key => $value)
    {
      if (array_search($key, $array) === false)
      {
        $value->setAccess(null);
      }
//      else
//      {
//        $value->setAccess($this->defaultAccess);
//      }
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
  
}