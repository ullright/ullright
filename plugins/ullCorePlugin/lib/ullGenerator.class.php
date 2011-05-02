<?php
/**
 * This is the base class for all ullGenerators
 * 
 * The basic idea: for a given table/model we have definitions on the 
 * table level (label, description, global access rights, etc) and on the
 * column level (label, description, access rights, required, show_in_list, etc)
 * 
 * Furthermore we define a highlevel "type" of field foreach column.
 * The ullMetaWidgets are used for that purpose. They configure a widget for
 * read access (e.g. for list view or read only fields), a widget for write access
 * and validators
 * 
 * Using these ullMetaWidgets a sfForm is built
 * 
 */

abstract class ullGenerator extends ullGeneratorBase
{
  protected
    $tableConfig    = array(),
    $columnsConfig  = array(),
    $activeColumns  = array(),
    $forms          = array(),
    $rows           = array(),
    $modelName,
    $isBuilt        = false,
    $formClass      = 'ullGeneratorForm',
    
    /**
     * Provide delete functionality
     */
    $allowDelete    = true,
    /**
     * Config flag if we want to build sums
     */
    $calculateSums      = false,
    /**
     * Array of sums
     */
    $sums           = array(),
    $sumForm        = null,
    $filterFormClass = 'ullFilterForm',
    $filterForm     = null
  ;
    
  /**
   * @see ullGeneratorBase::__construct()
   */  
  public function __construct($defaultAccess = null, $requestAction = null, $columns = array())
  {
    parent::__construct($defaultAccess, $requestAction);    

    $this->buildTableConfig();
    
    $this->buildColumnsConfig();
    
    $this->buildFilterForm();
    
    $this->configure();
  }
  
  
  /**
   * Configures the generator
   * 
   * @return none
   */
  public function configure()
  {
    
  }
  
  
  /**
   * Returns true if the current form has some associated i18n objects.
   *
   * @return Boolean true if the current form has some associated i18n objects, false otherwise
   */
  public function isI18n()
  {
    return $this->rows[0]->getTable()->hasTemplate('Doctrine_Template_I18n');
  }  

  /**
   * get the table config
   *
   * @return UllTableConfiguration
   */
  public function getTableConfig()
  {
    return $this->tableConfig;
  }
  
  /**
   * get the column config
   *
   * @return ullColumnConfigCollection
   */
  public function getColumnsConfig()
  {
    return $this->columnsConfig;
  }
  
  /**
   * set the column config
   *
   * @return array
   */
  public function setColumnsConfig($cc)
  {
    $this->columnsConfig = $cc;
  }  
  
  /**
   * get the embedded sfForm
   *
   * @return ullGeneratorForm
   * @throws RuntimeException
   */
  public function getForm()
  {
    if (!$this->isBuilt)
    {
      throw new RuntimeException('You have to call buildForm() first');
    }     
    
    return $this->forms[0];
  }
  
  /**
   * get the embedded forms
   *
   * @return array of sfForms
   * @throws RuntimeException
   */
  public function getForms()
  {
    if (!$this->isBuilt)
    {
      throw new RuntimeException('You have to call buildForm() first');
    }     
    
    return $this->forms;
  }
  
  /**
   * get first row
   *
   * @return Doctrine_Record
   * @throws RuntimeException
   */
  public function getRow()
  {
    if (!$this->isBuilt)
    {
      throw new RuntimeException('You have to call buildForm() first');
    } 
        
    return $this->rows[0];
  }
  
  /**
   * Get the specified row
   * 
   * @param $row the row index
   * @return Doctrine_Record
   */
  public function getSpecificRow($row = 0)
  {
    if (!$this->isBuilt)
    {
      throw new RuntimeException('You have to call buildForm() first');
    } 
        
    return $this->rows[$row];
  }
  
  /**
   * get Lables of the form fields
   *
   * @return array
   */
  public function getLabels()
  {   
    return $this->forms[0]->getWidgetSchema()->getLabels();
  }
  
  /**
   * Returns the labels getLabels() would return but without the ones
   * where the originating column's autoRender flag is set to false.
   * 
   * @return array of labels of columns with autoRender = false 
   */
  public function getAutoRenderedLabels()
  {
    $labels = $this->getLabels();
    
    $columns = $this->getAutoRenderedColumns();
    
    $labelKeys = array_keys($labels);
    $columnKeys = array_keys($columns);
    
    foreach ($labelKeys as $labelKey)
    {
      $translationPosition = strrpos($labelKey, '_translation_');
      if ($translationPosition !== false)
      {
        $originalColumnKey = substr($labelKey, 0, $translationPosition);
        if (isset($columns[$originalColumnKey]) && $columns[$originalColumnKey]->getTranslated() === true)
        {
          //this is a valid translated label
          continue;
        }
      }
      
      if (!(in_array($labelKey, $columnKeys)))
      {
        //this label is not to be rendered
        unset($labels[$labelKey]);
      }
    }
    
    return $labels;
  }
  
  /**
  * Returns the same labels as getAutoRenderedLabels() but instead
  * of a simple column => label array it returns the column names
  * with subarrays containing the label and the default order
  * direction.
  * 
  * Used by the result list header to provide a descending default
  * ordering, e.g. for a ratings-column.
  * 
  * @return column_name array of arrays with label and defaultOrderDirection
  */
  public function getAutoRenderedLabelsWithDefaultOrder()
  {
    $labelsWithDefaultOrder = array();
    $labels = $this->getAutoRenderedLabels();
    foreach ($labels as $labelKey => $labelValue)
    {
      $labelsWithDefaultOrder[$labelKey]['label'] = $labelValue;
      $defaultOrderDirection = (isset($this->columnsConfig[$labelKey])) ?
        $this->columnsConfig[$labelKey]->getDefaultOrderDirection() : null;
      $labelsWithDefaultOrder[$labelKey]['defaultOrderDirection'] = $defaultOrderDirection;
    }
    
    return $labelsWithDefaultOrder;
  }
  
  /**
   * builds the table config
   *
   */
  abstract protected function buildTableConfig();
  
  /**
   * builds the column config
   *
   */
  abstract protected function buildColumnsConfig();
  
  
  /**
   * Initializes the filter form
   * 
   * @return none
   */
  public function buildFilterForm()
  {
    // only for list mode
    if ($this->defaultAccess == 'w')
    {
      return;
    }
    
    $this->filterForm = new $this->filterFormClass;
    
    // legacy check    
    if (!method_exists($this->tableConfig, 'getFilterColumns'))
    {
      return;
    }
    
    $filterColumns = $this->tableConfig->getFilterColumns();
    
    foreach ($filterColumns as $filterColumn => $defaultValue)
    {
      $columnConfig = clone $this->columnsConfig[$filterColumn];
      $columnConfig->setAccess('s');
      
      $ullMetaWidgetClassName = $columnConfig->getMetaWidgetClassName();
      $ullMetaWidget = new $ullMetaWidgetClassName($columnConfig, $this->filterForm);
      $ullMetaWidget->addToFormAs($filterColumn);
      
      $this->filterForm->getWidgetSchema()->setLabel($filterColumn, $columnConfig->getLabel());
      
      $this->filterForm->setDefault($filterColumn, $defaultValue);
      
      $widget = $this->filterForm->getWidgetSchema()->offsetGet($filterColumn);
      
      // auto submit select boxes
      if ($widget instanceof sfWidgetFormSelect || 
        $widget instanceof sfWidgetFormChoice)
      {
        $widget->setAttribute('onchange', 'submit();');
      }
    }
    
//    $this->filterForm->debug();
  }

  /**
   * Sets the filter form
   * 
   * @return self
   */
  public function setFilterForm(sfForm $filterForm)
  {
    $this->filterForm = $filterForm;
    
    return $this; 
  }
    
  
  
  /**
   * Gets the filter form
   * 
   * @return ullFilterForm
   */
  public function getFilterForm()
  {
    return $this->filterForm;
  }
  
  
  /**
   * Sets the defaults for the filter form.
   * 
   * The sfForm defaults are only used when a form is not bound
   * Since the filter form is always bound, we simulate defaults
   * by injecting them into the request params before binding
   * the form.
   * 
   * A default is only set when the appropriate param is not given via request
   * 
   * @param array $filterParams
   * @return array
   */
  public function setFilterFormDefaults($filterParams)
  {
    $defaults = $this->filterForm->getDefaults();
    
    foreach($defaults as $fieldName => $value)
    {
      if ($value)
      {
        if ( ! (isset($filterParams[$fieldName]) && $filterParams))
        {
          $filterParams[$fieldName] = $value;
        }
      }
    }
    
    return $filterParams;
  }  
  
  
  /**
   * Applies the filter settings to the query and adds a "filter" tag when
   * approriate
   * 
   * @param ullQuery $q
   * @param ullFilter $ullFilter
   * @return none
   */
  public function addFilter(ullQuery $q, ullFilter $ullFilter)
  {
    $filterColumns = $this->tableConfig->getFilterColumns();
    
    foreach ($filterColumns as $filterColumn => $defaultValue)
    {
      $value = $this->filterForm->getValue($filterColumn);
      
      // ignore the manual "null" value (select boxes "empty" etc)
      if ($value && $value != '_all_')
      {
        $columnConfig = clone $this->columnsConfig[$filterColumn];
        $ullMetaWidgetClassName = $columnConfig->getMetaWidgetClassName();
        $ullMetaWidget = new $ullMetaWidgetClassName($columnConfig, $this->filterForm);
        $searchType = $ullMetaWidget->getSearchType();
        
        if ($searchType == 'boolean')
        {
          if ($value == 'checked')
          {
            $q->addWhere($filterColumn . ' = ?', true);
          }          
          elseif ($value == 'unchecked')
          {
            $q->addWhere($filterColumn . ' = ?', false);
          }
        }
        else
        {
          $q->addWhere($filterColumn . ' = ?', $value);
          
          // Parse the value using the appropriate read widget
          sfContext::getInstance()->getConfiguration()->loadHelpers(array('Escaping')); // required by some widgets
          $tempForm = new sfForm();
          $readColumnsConfig = clone $this->getColumnsConfig()->offsetGet($filterColumn);
          $readColumnsConfig->setAccess('f');
          $metaWidgetReadClassName = $readColumnsConfig->getMetaWidgetClassName();
          $metaWidgetRead = new $metaWidgetReadClassName($readColumnsConfig, $tempForm);
          $metaWidgetRead->addToFormAs($filterColumn);
          $tempForm->setDefault($filterColumn, $value);
          
          $outputValue = $tempForm->offsetGet($filterColumn)->render();
          
          $ullFilter->add('filter[' . $filterColumn . ']', $this->filterForm->getWidgetSchema()->getLabel($filterColumn) . ': ' . $outputValue);
        }
      }
    }
  }
    
 
  /**
   * builds the form
   *
   * @param array $rows array of Doctrine_Records
   */
  public function buildForm($rows)
  {
    if (is_array($rows))
    {
      $this->rows = $rows;
    }
    elseif ($rows instanceof Doctrine_Collection)
    {
      $this->rows = $rows;
    }
    else
    {
      $this->rows[] = $rows;
    }    
    
    if ($this->isI18n())
    {
      $cultures = self::getDefaultCultures();
    }
    else
    {
      $cultures = array();
    }

    foreach ($this->rows as $key => $row) 
    {
      $this->forms[$key] = new $this->formClass($row, $this->columnsConfig, $this->requestAction, $this->getDefaults(), $cultures);
      
      foreach ($this->getActiveColumns() as $columnName => $columnConfig)
      {
        $ullMetaWidgetClassName = $columnConfig->getMetaWidgetClassName();
        $ullMetaWidget = new $ullMetaWidgetClassName($columnConfig, $this->forms[$key]);
        
        if ($columnConfig->getTranslated() == true && !$this->isListAction())
        { 
          foreach ($cultures as $culture)
          {
            $translationColumnName = $columnName . '_translation_' . $culture;
            $ullMetaWidget->addToFormAs($translationColumnName);
            $label = $columnConfig->getLabel();
            if (count($cultures) > 1)
            {
              $label .= ' ' . strtoupper($culture);
            }
            $this->forms[$key]->getWidgetSchema()->setLabel($translationColumnName, $label);
            
            // help
            if ($columnConfig->getAccess() == 'w')
            {
              $this->forms[$key]->getWidgetSchema()->setHelp($translationColumnName, $columnConfig->getHelp());
            }            
          }
        }
        else
        {
          $ullMetaWidget->addToFormAs($columnName);
          $this->forms[$key]->getWidgetSchema()->setLabel($columnName, __($columnConfig->getLabel(), null, 'common'));
          
          // help
          if ($columnConfig->getAccess() == 'w')
          {
            $this->forms[$key]->getWidgetSchema()->setHelp($columnName, $columnConfig->getHelp());
          }
        }
        
        $this->calculateSum($columnName, $row);
      }
      
      $this->forms[$key]->updateDefaults();
      $this->forms[$key]->markMandatoryFields();
    }
    
    $this->buildSumForm($cultures);
    
    $this->isBuilt = true;
  }
  
 
  /**
   * get array of default cultures
   * 
   * this array includes the default culture (usually 'en') and the current 
   * user's culture if different from the default culture (e.g. 'de')
   * 
   * @return: array
   *
   */
  public static function getDefaultCultures()
  {
    $userCulture = substr(sfContext::getInstance()->getUser()->getCulture(), 0, 2);
    $defaultCulture = sfConfig::get('sf_default_culture', 'en');
    
    $cultures = array($defaultCulture);
    if ($defaultCulture != $userCulture)
    {
      $cultures[] = $userCulture;
    }
    
    return $cultures;
  }
  
  
  /**
   * Get default values 
   * 
   * @return array
   */
  public function getDefaults()
  {
    $defaults = array();

    foreach ($this->columnsConfig as $columnName => $columnConfig)
    {      
      
      if ($columnConfig->getDefaultValue() != null) 
      {
        $defaults[$columnName] = $columnConfig->getDefaultValue();
      }
    }

    return $defaults;
  }
  
  
  /**
   * Check if there are any columns
   * 
   * @return boolean
   */
  public function hasColumns()
  {
    return (count($this->columnsConfig) > 0) ? true : false;
  }
  
  
  /**
   * Get list of active columnConfigurations
   *
   * @return array active columns
   */
  public function getActiveColumns()
  {
    return $this->columnsConfig->getActiveColumns(); 
  }
  
  
  /**
   * Get a list of columnConfigurations for database fields
   * 
   * @return array of column configs
   */
  public function getDatabaseColumns()
  {
    return $this->columnsConfig->getDatabaseColumns();
  }
  
  /**
   * Get a list of unsortable columnConfigurations 
   * 
   * @return array of column configs where sortable == false
   */
  public function getUnsortableColumns()
  {
    return $this->columnsConfig->getUnsortableColumns();
  }
  
  /**
   * Get a list of active columnConfigurations that are marked
   * to be rendered automatically
   * 
   *  If the form is allready built, we include form fields that 
   *  have no columnConfiguration (e.g. 'password_again', etc)
   * 
   * @return array of columnConfigurations
   */
  public function getAutoRenderedColumns()
  {
    $autoRenderedColumns = $this->columnsConfig->getAutoRenderedColumns();
    
    $columns = array();
    
    if ($this->isBuilt)
    {
      $formFields = $this->getForm()->getWidgetSchema()->getPositions();
      
      foreach($formFields as $formField)
      {
        // We want the autorendered fields
        if (in_array($formField, array_keys($autoRenderedColumns)))
        {
          $columns[$formField] = $autoRenderedColumns[$formField];
        }    
        
        // But we also want fields that have no column config
        if (!in_array($formField, array_keys($this->getColumnsConfig()->getCollection())))
        {
          $columns[$formField] = new ullColumnConfiguration;
          $columns[$formField]->setSection(null);
        }
      }      
    }

    //    var_dump($columns);
    
    return $columns;
  }
  
  
  /**
   * Set allow delete flag
   * 
   * @param boolean $allowDelete
   * @return self
   */
  public function setAllowDelete($allowDelete)
  {
    $this->allowDelete = (boolean) $allowDelete;
    
    return $this;
  }
  
  
  /**
   * Get allow delete flag
   * 
   * @return boolean
   */
  public function getAllowDelete()
  {
    return $this->allowDelete;
  }  

  /**
   * tests if a column is enabled
   *
   * @param array $columnConfig
   * @return boolean
   */
  protected function isColumnEnabled($columnConfig)
  {
    if ($columnConfig->getAccess() != null)
    { 
      if($this->getRequestAction() == "list")
      {
        if ($columnConfig->getIsInList() == true)
        {
          return true;
        }
      }
      else
      {
        return true;
      }
     }
  }
  
  
  /**
   * Set the class name of the sfForm to use
   * 
   * @param string $name
   * @return self
   */
  public function setFormClassName($name)
  {
    $this->formClass = $name;
    
    return $this;
  }
  
  
  /**
   * Get the class name of the sfForm to use
   * 
   * @return string
   */
  public function getFormClassName()
  {
    return $this->formClass;      
  }

  /**
   * Setter for config option if to build sums
   * 
   * @param boolean $boolean
   * @return self
   */
  public function setCalculateSums($boolean)
  {
    $this->calculateSums = (boolean) $boolean;
    
    return $this;
  }
  
  /**
   * Getter for config option if to build sums
   * 
   * @return boolean
   */
  public function getCalculateSums()
  {
    return $this->calculateSums;
  }
  
  /**
   * Get array of sums for the active columns
   * 
   * @return array
   * @throws RuntimeException
   */
  public function getSums()
  {
    if (!$this->isBuilt)
    {
      throw new RuntimeException('You have to call buildForm() first');
    } 
    return $this->sums;
  }
  
  /**
   * Get the sum form
   * 
   * @return sfForm
   * @throws RuntimeException
   */
  public function getSumForm()
  {
    if (!$this->isBuilt)
    {
      throw new RuntimeException('You have to call buildForm() first');
    } 
    return $this->sumForm;
  }  
  
  /**
   * Calculate the sum for a column
   * 
   * @param string $columnName
   * @param Doctrine_Record $row
   * @return none
   */
  protected function calculateSum($columnName, Doctrine_Record $row)
  {
    if ($row->exists() && $this->getCalculateSums() && $this->columnsConfig[$columnName]->getCalculateSum())
    {
      if (isset($this->sums[$columnName]))
      {
        $this->sums[$columnName] += $row[$columnName];
      }
      else
      {
        $this->sums[$columnName] = $row[$columnName];
      }
    }    
  }
  
  /**
   * Build the sum form
   * 
   * @param array $cultures
   * @return none
   */
  protected function buildSumForm($cultures)
  {
    if ($this->rows[0]->exists() && $this->getCalculateSums())
    {
      $record = new $this->modelName;
      $record->fromArray($this->sums);
      $this->sumForm = new $this->formClass($record, $this->columnsConfig, $this->requestAction, $this->getDefaults(), $cultures);
      
      foreach ($this->getActiveColumns() as $columnName => $columnConfig)
      {
        $ullMetaWidgetClassName = $columnConfig->getMetaWidgetClassName();
        $ullMetaWidget = new $ullMetaWidgetClassName($columnConfig, $this->sumForm);
        $ullMetaWidget->addToFormAs($columnName);
        
        // Set values for artificial columns
        if ($columnConfig->getIsArtificial())
        {
          $this->sumForm->setDefault($columnName, $this->sums[$columnName]);   
        }
      }
    }
  }
}