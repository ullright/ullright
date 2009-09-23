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
     * Column to order by
     * 
     * Example: created_at
     */
    $sortColumns,
    
    /**
     * List of columns for the quick search
     * 
     * Example: user_name,email
     */
    $searchColumns = array()
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
   * Defined private because could be confused with setSerachColumns in child classes
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
   * Set result list order
   * 
   * This must be a valid column
   * 
   * Example: created_at
   * 
   * @param string $sortColumns
   * @return self
   */
  public function setSortColumns($sortColumns)
  {
    $this->sortColumns = $sortColumns;
    
    return $this;
  }
  
  
  /**
   * Get sortColumns
   * 
   * @return string
   */
  public function getSortColumns()
  {
    return $this->sortColumns;
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
   * Helper function to render a task center link for the admin index action
   * 
   * It uses the given image, and the tableConfig's name and description
   * 
   * @param string $modelName
   * @param string $image
   * @return string
   */
  public static function renderTaskCenterLink($modelName, $plugin, $image, $text = null)
  {
    $config = self::buildFor($modelName);
    
    $path =  '/' . $plugin . 'Theme' . sfConfig::get('app_theme_package', 'NG') . 'Plugin/images/' . $image;
    
    if (!$text)
    {
      $text = __('Manage', null, 'common') . ' ' . $config->getName();
    }
    
    return ull_tc_task_link($path, 'ullTableTool/list?table=' . $modelName, $text, array('title' => $config->getDescription())); 
  }
}