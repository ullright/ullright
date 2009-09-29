<?php
/**
 * This class describes a data column and is primarily
 * used by the generators to build a sfForm
 */
class ullColumnConfiguration
{
  protected
    //from ull_column_config table
    $columnName, //db column name  
    $label, 
    $help, //'description' in ull_column_config table
    $metaWidgetClassName    = 'ullMetaWidgetString',
    $isInList               = true,    // deprecated, since column config collections can configure for different actions
    $widgetOptions          = array(), //'options' in ull_column_config table
    $defaultValue,
    
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
    $showSpacerAfter        = false,
    $injectIdentifier       = false
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

  //below: only getters/setters/removers
  
  public function getColumnName()
  {
    return $this->columnName;
  }

  public function setColumnName($columnName)
  {
    $this->columnName = $columnName;
    
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
    return $this->customAttributes[$attributeName];
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

  public function setShowSpacerAfter($boolean)
  {
    $this->showSpacerAfter = (boolean) $boolean;

    return $this;
  }
  
  public function getShowSpacerAfter()
  {
    return $this->showSpacerAfter;    
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
  
}