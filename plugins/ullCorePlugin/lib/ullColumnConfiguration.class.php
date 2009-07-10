<?php
/**
 * This class describes a data column and is primarly
 * used by the generators.
 */
class ullColumnConfiguration
{
  protected
    //from ull_column_config table
    $columnName, //db column name  WHY?
    $label, 
    $help, //'description' in ull_column_config table
    $metaWidgetClassName    = 'ullMetaWidgetString',
    $isInList               = true, 
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
    $options                = array() //meta widget options
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

  public function parseDoctrineColumnObject(array $doctrineColumn, array $columnRelations, array $columnRelationsForeign = null)
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

    //set relations if not the primary key
    //first we check for regular 'forward' relations,
    //if there isn't one we try the 'backward' relations.
    //example: from user to his groups via entitygroup.
    if (!isset($doctrineColumn['primary']) || $this->columnName != 'id')
    {
      if (isset($columnRelations[$this->columnName]))
      {
        $this->relation = $columnRelations[$this->columnName];
        
        switch($this->relation['model'])
        {
          case 'UllUser': 
            $this->metaWidgetClassName = 'ullMetaWidgetUllEntity';
            $this->setOption('entity_classes', array('UllUser'));
            break;
          
          case 'UllGroup': 
            $this->metaWidgetClassName = 'ullMetaWidgetUllEntity';
            $this->setOption('entity_classes', array('UllGroup'));
            break;
          
          case 'UllEntity': 
            $this->metaWidgetClassName = 'ullMetaWidgetUllEntity';
            break;
            
          default:
            $this->metaWidgetClassName = 'ullMetaWidgetForeignKey';
        }
      }
      else {
        if ($columnRelationsForeign != null)
        {
          if (isset($columnRelationsForeign[$this->columnName]))
          {
            $this->metaWidgetClassName = 'ullMetaWidgetForeignKey';
            $this->relation = $columnRelationsForeign[$this->columnName];
            
            //resolve second level relations for many to many relationships
            $relations = Doctrine::getTable($columnRelationsForeign[$this->columnName]['model'])->getRelations();
            foreach ($relations as $relation)
            {
              if ($relation->getLocal() == $this->columnName)
              {
                //var_dump('new model: ' . $relation->getClass());
                 $this->relation['model'] = $relation->getClass();
                
                break;
              }
            }
            
          }
        }
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
  
  public function getHelp()
  {
    return $this->help;
  }

  public function setHelp($help)
  {
    $this->help = $help;
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
  }

  public function setOptions($options)
  {
    $this->options = $options;
  }

  public function removeOption($optionName)
  {
    unset($this->options[$optionName]);
  }  
  
}