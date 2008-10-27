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
 */
abstract class ullGenerator
{
  protected
    $tableConfig    = array(),
    $columnsConfig  = array(),
    $forms          = array(),
    $rows           = array(),
    $modelName,
    $isBuilt        = false,
    $defaultAccess,
    $formClass
  ;

  /**
   * Constructor
   *
   * @param string $modelName
   * @param string $defaultAccess can be "r" or "w" for read or write
   */
  public function __construct($modelName = null, $defaultAccess = 'r')
  {

    if ($modelName === null)
    {
      throw new InvalidArgumentException('A model must be supplied');
    }
    
    if (!class_exists($modelName))
    {
      throw new InvalidArgumentException('Invalid model: ' . $modelName);
    }
    
    $this->modelName = $modelName;
    
    $this->setDefaultAccess($defaultAccess);
    
    $this->buildTableConfig();
    
    $this->buildColumnsConfig();
    
  }

  /**
   * Get the model name of the data object
   *
   * @return string
   */
  public function getModelName()
  {
    return $this->modelName;
  }

//  /**
//   * Returns true if the current form has some associated i18n objects.
//   *
//   * @return Boolean true if the current form has some associated i18n objects, false otherwise
//   */
//  public function isI18n()
//  {
//    return $this->rows[0]->getTable()->hasTemplate('Doctrine_Template_I18n');
//  }  
  
  /**
   * set default access
   *
   * @param string $access can be "r" or "w" for read or write
   * @throws UnexpectedValueException
   */
  // makes no sense as a public function because it doesn't rebuild the columnsConfig etc
  protected function setDefaultAccess($access = 'r')
  {
    if (!in_array($access, array('r', 'w')))
    {
      throw new UnexpectedValueException('Invalid access type "'. $access .'. Has to be either "r" or "w"'); 
    }

    $this->defaultAccess = $access;
  }  

  /**
   * get default access
   *
   * @return string can be "r" or "w" for read or write
   */
  public function getDefaultAccess()
  {
    return $this->defaultAccess;
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
    
    $cultures = self::getDefaultCultures();
    
    foreach ($this->rows as $key => $row) 
    {
      $this->forms[$key] = new $this->formClass($row, $cultures);
      foreach ($this->columnsConfig as $columnName => $columnConfig)
      {
        if ($this->isColumnEnabled($columnConfig)) 
        {
          $ullMetaWidgetClassName = $columnConfig['metaWidget'];
          $ullMetaWidget = new $ullMetaWidgetClassName($columnConfig);
          
          if (isset($columnConfig['translation']))
          { 
            foreach ($cultures as $culture)
            {
              $translationColumnName = $columnName . '_translation_' . $culture;
              $this->forms[$key]->addUllMetaWidget($translationColumnName, $ullMetaWidget);
              $label = __('%1% translation %2%', array('%1%' => $columnConfig['label'], '%2%' => $culture), 'common');
              $this->forms[$key]->getWidgetSchema()->setLabel($translationColumnName, $label);
            }
          }
          else
          {
            $this->forms[$key]->addUllMetaWidget($columnName, $ullMetaWidget);
            $this->forms[$key]->getWidgetSchema()->setLabel($columnName, $columnConfig['label']);
          }
        }        
      }
    }
    
    $this->isBuilt = true;
  }
  
  /**
   * tests if a column is enabled
   *
   * @param array $columnConfig
   * @return boolean
   */
  protected function isColumnEnabled($columnConfig)
  {
    if ($columnConfig['access']) {
    
      if ($this->defaultAccess == 'w' || 
          ($this->defaultAccess == 'r' && $columnConfig['show_in_list']))
      {
        return true;
      }
    }
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
    $defaultCulture = sfConfig::get('base_default_language', 'en');
    
    $cultures = array($defaultCulture);
    if ($defaultCulture != $userCulture)
    {
      $cultures[] = $userCulture;
    }
    
    return $cultures;
  }  
  
}

?>