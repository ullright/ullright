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
    $activeColumns  = array(),
    $forms          = array(),
    $rows           = array(),
    $modelName,
    $isBuilt        = false,
    $defaultAccess,
    $formClass,
    $requestAction,
    $system_column_names_humanized = array (
      'id'                  => 'ID',
      'creator_user_id'     => 'Created by', 
      'created_at'          => 'Created at',
      'updator_user_id'     => 'Updated by',
      'updated_at'          => 'Updated at',
      'db_table_name'       => 'Table name',
      'db_column_name'      => 'Column name',
      'field_type'          => 'Field Type',
      'is_enabled'          => 'Enabled',
      'is_in_list'          => 'Show in list',
      'is_mandatory'        => 'Mandatory',
      'label'               => 'Label',
      'description'         => 'Description',
      'slug'                => 'Unique identifier',
      'options'             => 'Options',
      'ull_column_type_id'  => 'Type',
      'sequence'            => 'Sequence',
      'default_value'       => 'Default value',
    )    
  ;

  /**
   * Constructor
   *
   * @param string $defaultAccess can be "r" or "w" for read or write
   * @param string $requestAction sets the mode (list or edit)
   */
  public function __construct($defaultAccess = 'r', $requestAction = null)
  {
    $this->setDefaultAccess($defaultAccess);

    if ($requestAction === null)
    {
      if (sfContext::getInstance()->getRequest()->getParameter('action') == 'list')
      {
        $requestAction = 'list';
      }
      else
      {
        $requestAction = 'edit';
      }
    }
    
    $this->setRequestAction($requestAction);
    
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
   * set default access
   *
   * @param string $access can be "r" or "w" for read or write
   * @throws InvalidArgumentException
   */
  // makes no sense as a public function because it doesn't rebuild the columnsConfig etc
  protected function setDefaultAccess($access = 'r')
  {
    if (!in_array($access, array('r', 'w')))
    {
      throw new InvalidArgumentException('Invalid access type "'. $access .'. Has to be either "r" or "w"'); 
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
   * set request action
   * 
   * allows detection of the mode used
   *
   * @param string $action
   * @throws InvalidArgumentException
   */
  public function setRequestAction($action = 'list')
  {
    if (!in_array($action, array('list', 'edit')))
    {
      throw new InvalidArgumentException('Invalid request action "'. $action .'". Has to be either "list" or "edit"'); 
    }

    $this->requestAction = $action; 
  }
  
  /**
   * get request action
   *
   * @return string can be "list" or "edit"
   */
  public function getRequestAction()
  {
    return $this->requestAction;
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
      $this->forms[$key] = new $this->formClass($row, $this->requestAction, $this->getDefaults(), $cultures);
      
      foreach ($this->getActiveColumns() as $columnName => $columnConfig)
      {
        $ullMetaWidgetClassName = $columnConfig['metaWidget'];
        $ullMetaWidget = new $ullMetaWidgetClassName($columnConfig, $this->forms[$key]);
        
        // label
        if (isset($columnConfig['translation']))
        { 
          foreach ($cultures as $culture)
          {
            $translationColumnName = $columnName . '_translation_' . $culture;
            $ullMetaWidget->addToFormAs($translationColumnName);
            $label = $columnConfig['label'] . ' ' . strtoupper($culture);
            $this->forms[$key]->getWidgetSchema()->setLabel($translationColumnName, $label);
          }
        }
        else
        {
          $ullMetaWidget->addToFormAs($columnName);
          //var_dump($columnName);
          //$this->forms[$key]->getWidgetSchema()->setLabel($columnName, $columnConfig['label']);
          $this->forms[$key]->getWidgetSchema()->setLabel($columnName, __($columnConfig['label'], null, 'common'));
        }
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
      if (isset($columnConfig['default_value'])) 
      {
        $defaults[$columnName] = $columnConfig['default_value'];
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
    if ($this->activeColumns)
    {
      return $this->activeColumns;
    }
    
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
    if ($columnConfig['access'])
    { 
      if($this->getRequestAction() == "list")
      {
        if ($columnConfig['is_in_list'])
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
}