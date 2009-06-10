<?php
/**
 * This class describes a data column and is primarly
 * used by the generators.
 */
class ullColumnConfiguration
{
  private $columnName;
  private $label, $metaWidgetClassName,
  $access, $isInList, $validatorOptions,
  $widgetOptions, $widgetAttributes,
  $unique, $translated, $relation,
  $defaultValue, $allowCreate;
  private $customAttributes;

  /**
   * Returns a new column configuration object with default values.
   * @param $columnName The column name
   * @param $access The access ('r', 'w' or 's')
   * @return ullColumnConfiguration The new column configuration object
   */
  public function __construct($columnName = '', $access = 'w')
  {
    $this->widgetOptions = array();
    $this->widgetAttributes = array();
    $this->validatorOptions = array();
    $this->customAttributes = array();

    $this->label = $columnName;
    $this->metaWidgetClassName = 'ullMetaWidgetString';
    $this->access = $access;
    $this->isInList = true;
    $this->validatorOptions['required'] = false;
    $this->unique = false;
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

  public function parseDoctrineColumnObject(array $doctrineColumn, array $columnRelations)
  {
    $metaWidgetClassName = ullMetaWidget::getMetaWidgetClassName($doctrineColumn['type']);
    if ($metaWidgetClassName)
    {
      $this->metaWidgetClassName = $metaWidgetClassName;

      if ($this->metaWidgetClassName == 'ullMetaWidgetString')
      {
        if ($doctrineColumn['length'] > 255)
        {
          $this->metaWidgetClassName = 'ullMetaWidgetTextarea';
        }
        else
        {
          //uhm... which is it?
          $this->widgetAttributes['maxlength'] =  $doctrineColumn['length'];
          $this->validatorOptions['max_length'] = $doctrineColumn['length'];
        }
      }
    }

    if (isset($doctrineColumn['notnull']))
    {
      $this->validatorOptions['required'] = true;
    }

    if (isset($doctrineColumn['unique']))
    {
      $this->unique = true;
    }

    if (isset($doctrineColumn['primary']))
    {
      $this->access = 'r';
      $this->unique = true;
      $this->validatorOptions['required'] = true;
    }

    // set relations if not the primary key
    if (!isset($doctrineColumn['primary']) || $this->columnName != 'id')
    {
      if (isset($columnRelations[$this->columnName]))
      {
        $this->metaWidgetClassName = 'ullMetaWidgetForeignKey';
        $this->relation = $columnRelations[$this->columnName];
      }
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
  }

  public function getLabel()
  {
    return $this->label;
  }

  public function setLabel($label)
  {
    $this->label = $label;
  }

  public function getMetaWidgetClassName()
  {
    return $this->metaWidgetClassName;
  }

  public function setMetaWidgetClassName($metaWidgetClassName)
  {
    $this->metaWidgetClassName = $metaWidgetClassName;
  }

  public function getAccess()
  {
    return $this->access;
  }

  public function setAccess($access)
  {
    $this->access = $access;
  }

  public function getIsInList()
  {
    return $this->isInList;
  }

  public function setIsInList($isInList)
  {
    $this->isInList = $isInList;
  }

  public function getValidatorOptions()
  {
    return $this->validatorOptions;
  }

  public function setValidatorOption($validatorOptionName, $validatorOptionValue)
  {
    $this->validatorOptions[$validatorOptionName] = $validatorOptionValue;
  }

  public function setValidatorOptions($validatorOptions)
  {
    $this->validatorOptions = $validatorOptions;
  }

  public function removeValidatorOption($validatorOptionName)
  {
    unset($this->validatorOptions[$validatorOptionName]);
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
  }

  public function setWidgetOptions($widgetOptions)
  {
    $this->widgetOptions = $widgetOptions;
  }

  public function removeWidgetOption($widgetOptionName)
  {
    unset($this->widgetOptions[$widgetOptionName]);
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
  }

  public function setWidgetAttributes($widgetAttributes)
  {
    $this->widgetAttributes = $widgetAttributes;
  }

  public function removeWidgetAttribute($widgetAttributeName)
  {
    unset($this->widgetOptions[$widgetAttributeName]);
  }

  public function getUnique()
  {
    return $this->unique;
  }

  public function setUnique($unique)
  {
    $this->unique = $unique;
  }

  public function getTranslated()
  {
    return $this->translated;
  }

  public function setTranslated($translated)
  {
    $this->translated = $translated;
  }

  public function getRelation()
  {
    return $this->relation;
  }

  public function setRelation($relation)
  {
    $this->relation = $relation;
  }

  public function getDefaultValue()
  {
    return $this->defaultValue;
  }

  public function setDefaultValue($defaultValue)
  {
    $this->defaultValue = $defaultValue;
  }

  public function getAllowCreate()
  {
    return $this->allowCreate;
  }

  public function setAllowCreate($allowCreate)
  {
    $this->allowCreate = $allowCreate;
  }
  
  public function setCustomAttribute($attributeName, $attributeValue)
  {
    $this->customAttributes[$attributeName] = $attributeValue;
  }

  public function getCustomAttribute($attributeName)
  {
    return $this->customAttributes[$attributeName];
  }
}