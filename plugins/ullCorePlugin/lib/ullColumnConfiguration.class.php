<?php
/**
 * This class describes a data column and is primarily
 * used by the generators to build a sfForm
 */
class ullColumnConfiguration
{
  protected
    //manual
    $columnName, //db column name
    $modelName,  
    $label, 
    $help, //'description' in ull_column_config table
    $metaWidgetClassName    = 'ullMetaWidgetString',
    $isInList               = true,    // deprecated, since column config collections can configure for different actions
    $widgetOptions          = array(), //'options' in ull_column_config table
    $defaultValue,
    $naturalOrdering        = false,
    $defaultOrderDirection  = 'asc', //default to ascending order
    $isSortable             = true,
    
    //from model
    $unique                 = false, 
    $translated, 
    $relation,
    
    //set by generators
    $allowCreate,
    $customAttributes       = array(), //parameter holder, e.g. search form entry
    $access,
    $validatorOptions       = array('required' => false),   
    $widgetAttributes       = array(),
    $options                = array(), //meta widget options
    /*
     * Assign a field to a section
     * The edit view renders a horizontal spacer foreach new section
     * 
     * If set to null it is ignored and never initializes a new section
     */
    $section                = '',
    $injectIdentifier       = false,
    /*
     * Disable automatical rendering of a column in list/edit view
     *   to allow special handling
     */
    $autoRender             = true,
    /*
     * True if the current column is no native database column.
     */
    $isArtificial           = false,
    /**
     * Flag if we want to build a sum in list views
     */
    $calculateSum               = false
  ;

  /**
   * Returns a new column configuration object with default values.
   * @param $columnName The column name
   * @param $access The access ('r', 'w' or 's')
   * @return ullColumnConfiguration The new column configuration object
   */
  public function __construct($columnName = '', $access = 'w')
  {
    $this->label = $columnName;
    $this->access = $access;
    $this->columnName = $columnName;
  
    if (ullHumanizer::hasColumnNameHumanization($this->label))
    {
      $this->label = ullHumanizer::humanizeAndTranslateColumnName($this->label);
    }
    else
    {
      $this->label = sfInflector::humanize($this->label);
    }
  }

  
  public function getColumnName()
  {
    return $this->columnName;
  }

  public function setColumnName($columnName)
  {
    $this->columnName = $columnName;
    
    return $this;
  }
  
  public function getModelName()
  {
    return $this->modelName;
  }

  public function setModelName($modelName)
  {
    $this->modelName = $modelName;
    
    return $this;
  }

  public function getLabel()
  {
    return $this->label;
  }

  public function setLabel($label)
  {
    $this->label = $label;
    
    return $this;
  }
  
  public function getHelp()
  {
    return $this->help;
  }

  public function setHelp($help)
  {
    $this->help = $help;
    
    return $this;
  }  

  public function getMetaWidgetClassName()
  {
    return $this->metaWidgetClassName;
  }

  public function setMetaWidgetClassName($metaWidgetClassName)
  {
    $this->metaWidgetClassName = $metaWidgetClassName;
    
    return $this;
  }

  public function getAccess()
  {
    return $this->access;
  }

  public function setAccess($access)
  {
    $this->access = $access;
    
    return $this;
  }

  /**
   * Alias for set access = null
   * 
   * @return UllColumnConfiguration
   */
  public function disable()
  {
    $this->access = null;
    
    return $this;
  }  
  
  public function enable($access = 'r')
  {
    $this->access = ($access == 'r' || $access == 'w') ? $access : 'r';
    
    return $this;
  }  
  
  /**
   * A column is active when access is set
   * 
   * @return boolean
   */
  public function isActive()
  {
    if ($this->access)
    {
      return true;
    }
  }

  public function getNaturalOrdering()
  {
    return $this->naturalOrdering;
  }

  public function setNaturalOrdering($boolean)
  {
    $this->naturalOrdering = (boolean) $boolean;
    
    return $this;
  }
  
  public function getDefaultOrderDirection()
  {
    return $this->defaultOrderDirection;
  }

  public function setDefaultOrderDirection($defaultOrderDirection)
  {
    if ($defaultOrderDirection == 'asc' || $defaultOrderDirection == 'desc')
    {
      $this->defaultOrderDirection = $defaultOrderDirection;
    }
    else
    {
      throw new InvalidArgumentException("defaultOrderDirection must be either 'asc' or 'desc'");
    }  
    
    return $this;
  }
  
  public function getIsInList()
  {
    return $this->isInList;
  }

  public function setIsInList($isInList)
  {
    $this->isInList = $isInList;
    
    return $this;
  }

  public function getValidatorOption($optionName)
  {
    if (isset($this->validatorOptions[$optionName]))
    {
      return $this->validatorOptions[$optionName];
    }
    else
    {
      return null;
    }
  }  
  
  public function getValidatorOptions()
  {
    return $this->validatorOptions;
  }

  public function setValidatorOption($validatorOptionName, $validatorOptionValue)
  {
    $this->validatorOptions[$validatorOptionName] = $validatorOptionValue;
    
    return $this;
  }

  public function setValidatorOptions($validatorOptions)
  {
    $this->validatorOptions = $validatorOptions;
    
    return $this;
  }

  public function removeValidatorOption($validatorOptionName)
  {
    unset($this->validatorOptions[$validatorOptionName]);
    
    return $this;
  }

  public function getWidgetOption($optionName)
  {
    if (isset($this->widgetOptions[$optionName]))
    {
      return $this->widgetOptions[$optionName];
    }
    else
    {
      return null;
    }
  }

  public function getWidgetOptions()
  {
    return $this->widgetOptions;
  }

  public function setWidgetOption($widgetOptionName, $widgetOptionValue)
  {
    $this->widgetOptions[$widgetOptionName] = $widgetOptionValue;
    
    return $this;
  }

  public function setWidgetOptions($widgetOptions)
  {
    $this->widgetOptions = $widgetOptions;
    
    return $this;
  }

  public function removeWidgetOption($widgetOptionName)
  {
    unset($this->widgetOptions[$widgetOptionName]);
    
    return $this;
  }

  public function getWidgetAttribute($attributeName)
  {
    if (isset($this->widgetAttributes[$attributeName]))
    {
      return $this->widgetAttributes[$attributeName];
    }
    else
    {
      return null;
    }
  }

  public function getWidgetAttributes()
  {
    return $this->widgetAttributes;
  }

  public function setWidgetAttribute($attributeName, $attributeValue)
  {
    $this->widgetAttributes[$attributeName] = $attributeValue;
    
    return $this;
  }

  public function setWidgetAttributes($widgetAttributes)
  {
    $this->widgetAttributes = $widgetAttributes;
    
    return $this;
  }

  public function removeWidgetAttribute($attributeName)
  {
    unset($this->widgetAttributes[$attributeName]);
    
    return $this;
  }

  public function getUnique()
  {
    return $this->unique;
  }

  public function setUnique($unique)
  {
    $this->unique = $unique;
    
    return $this;
  }

  public function getTranslated()
  {
    return $this->translated;
  }

  public function setTranslated($translated)
  {
    $this->translated = $translated;
    
    return $this;
  }

  public function getRelation()
  {
    return $this->relation;
  }

  public function setRelation($relation)
  {
    $this->relation = $relation;
    
    return $this;
  }

  public function getDefaultValue()
  {
    return $this->defaultValue;
  }

  public function setDefaultValue($defaultValue)
  {
    $this->defaultValue = $defaultValue;
    
    return $this;
  }

  public function getAllowCreate()
  {
    return $this->allowCreate;
  }

  public function setAllowCreate($allowCreate)
  {
    $this->allowCreate = $allowCreate;
    
    return $this;
  }
  
  public function setCustomAttribute($attributeName, $attributeValue)
  {
    $this->customAttributes[$attributeName] = $attributeValue;
    
    return $this;
  }

  public function getCustomAttribute($attributeName)
  {
    if (isset($this->customAttributes[$attributeName]))
    {
      return $this->customAttributes[$attributeName];
    }
    else
    {
      return null;
    }
  }
  
  public function getOption($optionName)
  {
    if (isset($this->options[$optionName]))
    {
      return $this->options[$optionName];
    }
    else
    {
      return null;
    }
  }

  public function getOptions()
  {
    return $this->options;
  }

  public function setOption($optionName, $optionValue)
  {
    $this->options[$optionName] = $optionValue;
    
    return $this;
  }

  public function setOptions($options)
  {
    $this->options = $options;
    
    return $this;
  }

  public function removeOption($optionName)
  {
    unset($this->options[$optionName]);
    
    return $this;
  }

  public function setSection($section)
  {
    $this->section = $section;

    return $this;
  }
  
  public function getSection()
  {
    return $this->section;    
  }
  
  public function setInjectIdentifier($boolean)
  {
    $this->injectIdentifier = (boolean) $boolean;
    
    return $this;
  }
  
  public function getInjectIdentifier()
  {
    return $this->injectIdentifier;
  }
  
  /*
   * Gets the autoRender flag (whether the view should render the columns or not)
   * 
   * @return boolean
   */
  public function getAutoRender()
  {
    return $this->autoRender;
  }
  
  /*
   * Sets the autoRender flag (whether the view should render the columns or not)
   * 
   * @return boolean
   */
  public function setAutoRender($boolean)
  {
    $this->autoRender = (boolean) $boolean;
    
    return $this;
  }
  
  /**
   * Set if the current column is artificial (no database column)
   * 
   * @param boolean $boolean
   * @return self
   */
  public function setIsArtificial($boolean)
  {
    $this->isArtificial = (boolean) $boolean;
    
    return $this;
  }
  
  
  /**
   * Get if the current column is artificial (no database column)
   * 
   * @return boolean
   */
  public function getIsArtificial()
  {
    return $this->isArtificial;
  }
    
  
  /**
   * Build sum for list views?
   * 
   * @param boolean $boolean
   * @return self
   */
  public function setCalculateSum($boolean)
  {
    $this->calculateSum = (boolean) $boolean;
    
    return $this;
  }
  
  
  /**
   * Get if we want to build sum
   * 
   * @return boolean
   */
  public function getCalculateSum()
  {
    return $this->calculateSum;
  }  
  
  
  /**
   * Set required flag
   * @param boolean $boolean
   */
  public function setIsRequired($boolean)
  {
    $this->setValidatorOption('required', (boolean) $boolean);
    
    return $this;
  }
  
  
  /**
   * Get required flag
   * @param $boolean
   */
  public function getIsRequired()
  {
    return (boolean) $this->getValidatorOption('required');
  }
  
  /**
   * Set the sortable attribute
   * 
   * @param boolean $boolean
   * @return self
   */
  public function setIsSortable($boolean)
  {
    $this->isSortable = (boolean) $boolean;
    
    return $this;
  }
  
  
  /**
   * Get the sortable attribute
   * 
   * @return boolean
   */
  public function getIsSortable()
  {
    return $this->isSortable;
  }
  
  /**
   * Enable ajax update function for the current field
   * 
   * @param boolean $boolean
   */
  public function setAjaxUpdate($boolean)
  {
    $this->setOption('enable_ajax_update', $boolean);
    
    if (true === $boolean)
    {
      $this->setInjectIdentifier(true);
      $this->setOption('ajax_url', 'ullTableTool/updateSingleColumn');
      $this->setOption('ajax_model', $this->getModelName());
      $this->setOption('ajax_column', $this->getColumnName());
    } 
    else
    {
      $this->removeOption('ajax_url');
      $this->removeOption('ajax_model');
      $this->removeOption('ajax_column');
    }
    
    return $this;
  }
  
  
  /**
   * Check if ajax update is enabled
   * 
   * @return boolean 
   */
  public function getAjaxUpdate()
  {
    return (boolean) $this->getOption('enable_ajax_update');
  }
  
  /**
   * Mark a field as advanced form field.
   * 
   * This means it is hidden per default and replaced with a link to 
   * "advanced options" by js
   * 
   */
  public function markAsAdvancedField()
  {
    $this->setWidgetAttribute(
      'class', 
      $this->getWidgetAttribute('class') . ' advanced_form_field'
    ); 
    
    return $this;
  }
}