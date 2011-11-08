<?php 

/**
 * The ullTableConfiguration class represents settings on the database table level
 * for the ullTableToolGenerator like a humanized name, a description, sort and search columns
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class ullTableConfiguration
{

  protected
    $modelName,
    $table,
    
    /**
     * Human readable and translated name of the table
     */
    $name,
    
    /**
     * Additional description for the usage of the table
     */
    $description,
    
    /**
     * Doctrine orderBy string, 
     * Also supports relation columns
     * 
     * Example: created_at DESC, priority, Creator->username
     */
    $orderBy,
    
    /**
     * List of columns for the quick search
     * 
     * Example: array('user_name', 'email')
     */
    $searchColumns = array(),
    
    /**
     * List of columns which are shown in the list action per default
     * 
     * TODO: refactor into a parameter holder to allow configuring
     * columns for arbitrary actions? e.g. "createWithType"
     * 
     * Example: array('last_name', 'UllLocation->street');
     */
    $listColumns = array(),
    
    /**
     * List of columns which are shown in the edit action per default
     * 
     * TODO: Do edit columns make sense?
     * 
     * Example: array('last_name', 'UllLocation->street');
     */
    $editColumns = array(),    
    
    
    /**
     * Name to use as default field for string representation for this table
     */
    $toStringColumn = '',
    
    
    /**
     * Dictionary for custom relation names
     * 
     * Format: array($relationAsString => $humanizedName)
     * 
     * Example for UllVentoryItem: 
     * array(
     *   'UllVentoryItemModel->UllVentoryItemType' => 'Type',
     * )
     * 
     */
    $customRelationNames = array(),
    
    /**
     * Dictionary for humanized foreign relation names
     * Tables with a relation to this table should use the appropriate humanized name:
     * 
     * Format: array($relationName => $humanizedName)
     * 
     * Example for UllUser: 
     * array(
     *   'UllUser'   => 'User',
     *   'Creator'   => 'Created by',
     * ) 
     *  
     */
    $foreignRelationNames = array(),
    
    /**
     * Configures for which columns filter inputs are displayed
     */
    $filterColumns = array(),
    
    /**
     * Define to which symfony plugin this table belongs
     * 
     * Used e.g. to load the correct plugin stylesheet in tabletool
     */
    $plugin = null,

    /**
     * Breadcrumb class for tabletool
     */
    $breadcrumbClass = null
  ;
  
  
  /**
   * Constructor
   * 
   * @param string $modelName
   * @return none
   */
  public function __construct($modelName)
  {
    $this->modelName = $modelName;
    $this->table = Doctrine::getTable($this->modelName); 
  }
  
  
  /**
   * Default static method to get a ullTableConfiguration for a model
   * 
   * It's possible to customize per model by providing a myModelTableConfiguration class
   * 
   * @param string $modelName                 Doctrine model name e.g. 'TestTable'
   * @return ullTableConfiguration
   */
  public static function buildFor($modelName)
  {
    $className = $modelName . 'TableConfiguration';
    
    if (class_exists($className))
    {
      $c = new $className($modelName);
    }
    else
    {
      $c = new self($modelName);
    }
    
    $c->build();
    
    return $c;
  }  
  
  
  /**
   * Builds the tableConfiguration
   * 
   * @return none
   */
  protected function build()
  {
    $this->applyCommonSettings();
    
    $this->applyDoctrineSettings();
    
    $this->applyCustomSettings();
  }
  
  
  /**
   * Applies common settings
   * @return unknown_type
   */
  protected function applyCommonSettings()
  {
    $this->name = $this->getModelName();
  }
  
  
  /**
   * Apply settings from Doctrine Table
   * 
   * @return none
   */
  protected function applyDoctrineSettings()
  {
    $this->setDefaultSearchColumns();
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
   * Set the identifier as default search column
   * 
   * Defined private because could be confused with setSearchColumns in child classes
   * 
   * @return none
   */
  private final function setDefaultSearchColumns()
  {
    $searchColumns = $this->getIdentifier();
    
    if (!is_array($searchColumns))
    {
      $searchColumns = array($searchColumns);
    }

    $this->searchColumns = $searchColumns;        
  }
  
  
  /**
   * Return the identifier of the current table 
   *
   * @return mixed
   */
  public function getIdentifier()
  {
    return $this->table->getIdentifier();
  }
  
  /**
   * Get the modelName
   * 
   * @return string
   */
  public function getModelName()
  {
    return $this->modelName;
  }
  
  
  /**
   * Set the human readable translated table name
   * 
   * @param string $name
   * @return self
   */
  public function setName($name)
  {
    $this->name = $name;
    
    return $this;
  }
  
  
  /**
   * Get the human readable translated table name
   * 
   * @return string
   */
  public function getName()
  {
    return $this->name;
  }
  
  
  /**
   * Set description
   * 
   * @param string $description
   * @return self
   */
  public function setDescription($description)
  {
    $this->description = $description;
    
    return $this;
  }
  
  
  /**
   * Get description
   * 
   * @return unknown_type
   */
  public function getDescription()
  {
    return $this->description;
  }
  
  
  /**
   * Set default result list orderBy
   * 
   * This must be a valid doctrine orderBy string
   * 
   * Example: created_at DESC, priority, Creator->username
   * 
   * @param string $sortColumns
   * @return self
   */
  public function setOrderBy($orderBy)
  {
    $this->orderBy = $orderBy;
    
    return $this;
  }
  
  
  /**
   * Get default orderBy 
   * 
   * @return string
   */
  public function getOrderBy()
  {
    return $this->orderBy;
  }
  
  
  /**
   * Set searchColumns
   * 
   * list of columns for the quick search
   * Example: array('user_name', 'email') 
   * 
   * @param array $sortColumns
   * @return self
   */
  public function setSearchColumns(array $searchColumns)
  {
    $this->searchColumns = $searchColumns;
  
    return $this;
  }
  
  
  /**
   * Get searchColumns
   * 
   * @return array
   */
  public function getSearchColumns()
  {
    return $this->searchColumns;
  }
  
  
  /**
   * Return the searchColumns as an array
   * 
   * Temp. alias legacy function for the ullTableConfiguration migration
   *
   * @return array
   */
  public function getSearchColumnsAsArray()
  {
    return $this->searchColumns;
  }  
  
  /**
   * Sets filter columns and default values
   * 
   * list of columns for which we want filters
   * 
   * Example: array('user_name' => null, 'is_active' => 'checked') 
   * 
   * Supports relation columns as well, but they must be set as list columns as well
   * 
   * @param array $filterColumns
   * @return self
   */
  public function setFilterColumns(array $filterColumns)
  {
    $this->filterColumns = $filterColumns;
  
    return $this;
  }
  
  
  /**
   * Get filter columns
   * 
   * @return array
   */
  public function getFilterColumns()
  {
    return $this->filterColumns;
  }  
  
  
  /**
   * Set the default list columns
   * 
   * @param array $columns
   * @return self
   */
  public function setListColumns(array $columns)
  {
    // Deactivated because it's difficult to check e.g. artificial columns
    //   We would have to force a column name syntax (e.g. 'artificial_my_column')
    //   or we'd have to check the columnConfig for ->getArtificial()
//    ullGeneratorTools::validateColumnNames($this->getModelName(), $columns);
    
    $this->listColumns = $columns;
    
    return $this;
  }
  
  
  /**
   * Get the default list columns
   * 
   * @return array
   */
  public function getListColumns()
  {
    return $this->listColumns;
  }
  
  
  /**
   * Set the default Edit columns
   * 
   * @param array $columns
   * @return self
   */
  public function setEditColumns(array $columns)
  {
    ullGeneratorTools::validateColumnNames($this->getModelName(), $columns);
    
    $this->editColumns = $columns;
    
    return $this;
  }
  
  
  /**
   * Get the default Edit columns
   * 
   * @return array
   */
  public function getEditColumns()
  {
    return $this->editColumns;
  }  
  
  
  /** 
   * Sets the column which should be used for string representation
   * 
   * @param string $name
   * @return self
   */
  public function setToStringColumn($name)
  {
    $this->toStringColumn = $name;
    
    return $this;
  }
  
  /**
   * Gets the column which should be used for string representation
   * 
   * If non is set, we guess.
   * 
   * @return: string
   */
  public function getToStringColumn()
  {
    if (!$this->toStringColumn)
    {
      if ($this->table->hasColumn('name'))
      {
        $this->toStringColumn = 'name';
      }
      else
      {
        throw new InvalidArgumentException('Please set the "to string column" in the table config for ' . $this->getModelName());
      }
    }
    
    return $this->toStringColumn;  
  }
  

  
  /**
   * Set a custom relation name
   * 
   * @param string $name
   * @param mixed $relation   array of relations or string representation
   * @return self
   */
  public function setCustomRelationName($relation, $name)
  {
    if (is_array($relation))
    {
      $relation = ullGeneratorTools::relationArrayToString($relation);
    }
    
    $this->customRelationNames[$relation] = $name;
    
    return $this;
  }  
  
  
  /**
   * Get a custom relation name
   * 
   * @param mixed $relation   array of relations or string representation
   * @return string
   */
  public function getCustomRelationName($relation)
  {
    if (is_array($relation))
    {
      $relation = ullGeneratorTools::relationArrayToString($relation);
    }
    
    if (isset($this->customRelationNames[$relation]))
    {
      return $this->customRelationNames[$relation];
    }
  }
  
  
  /**
   * Set a name that tables which have a relation to this table
   * should use.
   * 
   * @param $name
   * @param $relation
   * @return unknown_type
   */
  public function setForeignRelationName($name, $relation = null)
  {
    if ($relation === null)
    {
      $relation = $this->modelName;
    }
    
    $this->foreignRelationNames[$relation] = $name;
    
    return $this;
  }
  
  
  /**
   * Get the humanized name for a given foreign relation.
   * That is the relation name, that tables which have a relation to this
   * table should use.
   * 
   * If a match is found in the dictionary -> use it.
   * Otherwise try the generic relation humanization.
   * 
   * @param $name   optional Default relation name is the current model name.
   * @return none
   */  
  public function getForeignRelationName($relation = null)
  {
    if ($relation === null)
    {
      $relation = $this->modelName;
    }
    
    if (isset($this->foreignRelationNames[$relation]))
    {
      return $this->foreignRelationNames[$relation];
    }
    else
    {
      return ullHumanizer::humanizeAndTranslateRelation($relation);
    }
  }
  
  /**
   * Set to which symfony plugin this table belongs
   * 
   * @param string $plugin
   */
  public function setPlugin($plugin)
  {
    $this->plugin = $plugin;
    
    return $this;
  }
  
  /**
   * Get the symfony plugin name for this table
   * 
   * @param string $plugin
   */
  public function getPlugin()
  {
    return $this->plugin;
  }
  
  /**
   * Set tabletool breadcrumb class
   * 
   * @param string $breadcrumbs
   */
  public function setBreadcrumbClass($breadcrumbClass)
  {
    $this->breadcrumbClass = $breadcrumbClass;
    
    return $this;
  }
  
  /**
   * Get the tabletool breadcrumb class
   * 
   * @return string
   */
  public function getBreadcrumbClass()
  {
    return $this->breadcrumbClass;
  }
  
  
  /**
   * Helper function to render a task center link for the admin index action
   * 
   * It uses the given image, and the tableConfig's name and description
   * 
   * @param string $modelName
   * @param string $image
   * @return string
   */
  public static function renderTaskCenterLink($modelName, $plugin = null, $image = null, $text = null)
  {
    $config = self::buildFor($modelName);
    
    if (!$plugin)
    {
      $plugin = $config->getPlugin();
      $plugin = str_replace('Plugin', '', $plugin);
    }
    
    if (!$plugin)
    {
      throw new InvalidArgumentException('$plugin must be given');
    }
    
    if (!$image)
    {
      $image = 'action_icons/admin_24x24';
    }
    
    $path =  '/' . $plugin . 'Theme' . sfConfig::get('app_theme_package', 'NG') . 'Plugin/images/' . $image;
    
    if (!$text)
    {
      $text = __('Manage', null, 'common') . ' ' . $config->getName();
    }
    
    return ull_tc_task_link($path, 'ullTableTool/list?table=' . $modelName, $text, array('title' => $config->getDescription())); 
  }
}