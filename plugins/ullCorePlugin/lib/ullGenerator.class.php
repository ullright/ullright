<?php
/**
 * This is the base class for all ullGenerators
 * 
 * The basic idea: for a given table/model we have a definitions on the 
 * table level (label, description, global access rights, etc) and on the
 * column level (label, description, access rights, mandatory, show_in_list, etc)
 * 
 * Furthermore we define a highlevel "type" of field foreach column.
 * The ullMetaWidgets are used for that purpose. They configure a widget for
 * read access (e.g. for list view or read only fields), a widget for write access
 * and validators
 * 
 * Using this ullMetaWidgets a sfForm is built
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
    $formClass
    ;

  /**
   * @see ullGeneratorBase::__construct()
   */  
  public function __construct($defaultAccess = null, $requestAction = null)
  {
    parent::__construct($defaultAccess, $requestAction);    

    $this->buildTableConfig();
    
    $this->buildColumnsConfig();
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
   * @return UllTableConfig
   */
  public function getTableConfig()
  {
    return $this->tableConfig;
  }
  
  /**
   * get the column config
   *
   * @return array
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
   * @return sfForm
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
   * get Lables of the form fields
   *
   * @return array
   */
  public function getLabels()
  {   
    return $this->forms[0]->getWidgetSchema()->getLabels();
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
    //var_dump($this->getActiveColumns());
    foreach ($this->rows as $key => $row) 
    {
      $this->forms[$key] = new $this->formClass($row, $this->requestAction, $this->getDefaults(), $cultures);
      
      foreach ($this->getActiveColumns() as $columnName => $columnConfig)
      {
        $ullMetaWidgetClassName = $columnConfig->getMetaWidgetClassName();
        $ullMetaWidget = new $ullMetaWidgetClassName($columnConfig, $this->forms[$key]);
        
        if ($columnConfig->getTranslated() == true)
        { 
          foreach ($cultures as $culture)
          {
            $translationColumnName = $columnName . '_translation_' . $culture;
            $ullMetaWidget->addToFormAs($translationColumnName);
            $label = $columnConfig->getLabel() . ' ' . strtoupper($culture);
            $this->forms[$key]->getWidgetSchema()->setLabel($translationColumnName, $label);
          }
        }
        else
        {
          $ullMetaWidget->addToFormAs($columnName);
          $this->forms[$key]->getWidgetSchema()->setLabel($columnName, __($columnConfig->getLabel(), null, 'common'));
        }
        
        //help
        $this->forms[$key]->getWidgetSchema()->setHelp($columnName, $columnConfig->getHelp());
        
        $this->markMandatoryColumns($this->forms[$key], $columnName, $columnConfig);
      }
    }
    
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

  public function hasColumns()
  {
    return (count($this->columnsConfig) > 0) ? true : false;
  }
  
  /**
   * get array containing the active columns
   *
   * @return array active columns
   */
  public function getActiveColumns()
  {
//    if ($this->activeColumns)
//    {
//      return $this->activeColumns;
//    }
    
    $this->activeColumns = array();
    
    foreach ($this->columnsConfig as $columnName => $columnConfig)
    {
      if ($this->isColumnEnabled($columnConfig)) 
      {
        $this->activeColumns[$columnName] = $columnConfig;
      }
    }
    
    return $this->activeColumns;
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
   * mark the label of mandatory fields with an asterix "*"
   * 
   * @param $form
   * @param $columnName
   * @param $columnConfig
   * @return none
   */
  protected function markMandatoryColumns(sfForm $form, $columnName, ullColumnConfiguration  $columnConfig)
  {
    if ($columnConfig->getAccess() == 'w' && $form->offsetExists($columnName))
    {
      $label = $form->getWidgetSchema()->getLabel($columnName);
      $validatorOptions = $columnConfig->getValidatorOptions();
      if ($validatorOptions["required"] === true)
      {
        $form->getWidgetSchema()->setLabel($columnName, $label . ' *');  
      }
    }
  }
  
}