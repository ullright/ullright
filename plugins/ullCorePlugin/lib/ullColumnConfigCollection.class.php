<?php 

class ullColumnConfigCollection implements ArrayAccess, Countable, IteratorAggregate
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
    $action,
    
    $blacklist = array(
          'namespace',
          'type',
    ),
    // columns which should be displayed at last position
    $orderBottom = array(
          'creator_user_id',
          'created_at',
          'updator_user_id',
          'updated_at',
    ),
    $readOnly = array(
          'creator_user_id',
          'created_at',
          'updator_user_id',
          'updated_at',
    ),
    $notInList = array(
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
   * @param $action string symfony controller action. one of 'list', 'create', 'edit'
   * @return none
   */
  public function __construct($modelName, $action = 'edit')
  {
    $this->modelName = $modelName;
    $this->action = $action;
  }
  
  /**
   * Default static method to get a ullColumnConfigCollection for a model
   * 
   * @param $modelName string Doctrine model name e.g. 'TestTable'
   * @param $action string symfony controller action. one of 'list', 'create', 'edit'
   * @return UllColumnConfigurationCollection
   */
  public static function buildFor($modelName, $action = 'edit')
  {
    $c = new self($modelName, $action);
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
   * Fill collection array with empty ullColumnConfigurations for each
   * model column
   * 
   * @return none
   */
  protected function createColumnConfigs()
  {
    foreach ($this->columns as $columnName => $column)
    {
      $this->collection[$columnName] = new ullColumnConfiguration;
    }
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
    $this->columns = Doctrine::getTable($this->modelName)->getColumns();
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
        ->setAccess($this->getDefaultAccessByAction())
        ->setLabel(ullHumanizer::humanizeAndTranslateColumnName($columnName))
      ;
    }

    $this->blacklist();
    $this->setReadOnly();
    $this->orderBottom($this->orderBottom);    
  }
  
  

  /**
   * Returns an access type string according to the action
   * 
   * @return string ('r' for read / 'w' for write access)
   */
  protected function getDefaultAccessByAction()
  {
    if (in_array($this->action, array('create', 'edit')))
    {
      return 'w';
    }
    
    return 'r';
  }

  /**
   * Remove unwanted columns
   *
   */
  protected function blacklist()
  {
    foreach ($this->blacklist as $column)
    {
      if (isset($this->collection[$column]))
      {
        $this->collection[$column]->disable();
      }
    }
  }
  
  
  /**
   * Set columns which are always read only
   *
   */
  protected function setReadOnly()
  {
    foreach($this->readOnly as $column)
    {
      $this->collection[$column]->setAccess('r');
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
        $columnConfig->setMetaWidgetClassName = 'ullMetaWidgetTextarea';
      }
      else
      {
        $columnConfig
          ->setWidgetAttribute('maxlength', $length)
          ->setValidatorOption('max_length', $length)
        ;
      }
    }  
    
//    var_dump($this->columnConfigColumns['my_email']);die;

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
        ->setAccess('r')
        ->setUnique(true)
        ->setValidatorOption('required', true)
      ;
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
   * Sets the symfony controller action
   * 
   * @param $action string
   * @return none
   */
  public function setAction($action)
  {
    $this->action = $action;
  }
  
  /**
   * Get the symfony controller action
   * 
   * @return string
   */
  public function getAction()
  {
    return $this->action;
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
      ->setAccess($this->getDefaultAccessByAction())
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
      $this->collection[$columnName]->setAccess(false);     
    }
  }
  
  /**
   * Returns true if the current action is 'list'
   * 
   * @return boolean
   */
  public function isListAction()
  {
    if ($this->action == 'list')
    {
      return true;   
    }  
  }
  
  /**
   * Returns true if the current action is 'create'
   * 
   * @return boolean
   */  
  public function isCreateAction()
  {
    if ($this->action == 'create')
    {
      return true;   
    }  
  }  

  /**
   * Returns true if the current action is 'edit'
   * 
   * @return boolean
   */
  public function isEditAction()
  {
    if ($this->action == 'edit')
    {
      return true;   
    }  
  }  

  /**
   * Returns true if the current action is 'create' or 'edit'
   * 
   * @return boolean
   */  
  public function isCreateOrEditAction()
  {
    if ($this->isCreateAction() || $this->isEditAction())
    {
      return true;
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
  
}