<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PluginUllRecordTable extends Doctrine_Table
{

  protected
    /*
     * ullColumnConfigCollection
     */ 
    $columnsConfig,
    
    /*
     * Array of columns to be included in the columnsConfigs
     * 
     * Format similiar to Doctrine::getTable('MyModel')->getColumns()
     * 
     *  array(
     *    "column_name" =>
     *      array(
     *        "type"          => "integer"
     *        "length"        => 20
     *        "autoincrement" => true
     *        "primary"       => true
     *      )
     *  )  
     * 
     */
    $columnConfigColumns = array(),
    
    $columnConfigRelations = array(),
    
    /*
     * controller action (list, edit, ...) 
     */
    $columnConfigAction, 
    
    $columnsBlacklist = array(
          'namespace',
          'type',
    ),
    // columns which should be displayed last
    $columnsOrderBottom = array(
          'creator_user_id',
          'created_at',
          'updator_user_id',
          'updated_at',
    ),
    $columnsReadOnly = array(
          'creator_user_id',
          'created_at',
          'updator_user_id',
          'updated_at',
    ),
    $columnsNotShownInList = array(
          'creator_user_id',
          'created_at',
          'updator_user_id',
          'updated_at',
    )
  ;
  

  /**
   * Returns the columnConfigs for the current model.
   * 
   * It applies several levels of configuration:
   *    1) Common settings
   *        - Common labels + translations
   *        - disabled
   *        - isInList
   *    2) Doctrine schema settings
   *        - not_null
   *        - unique
   *        - type (metaWidget)
   *        - length
   *        - relations
   *    3) Custom settings by overwriting applyCustomColumnConfigSettings()
   * 
   * @return array
   */
  public function getColumnsConfig($action = 'edit')
  {
    $this->columnConfigAction = $action;
    
    $this->createColumnsConfig();
    
    $this->applyCommonColumnConfigSettings();
      
    $this->applyDoctrineColumnConfigSettings();
      
    $this->applyCustomColumnConfigSettings();
    
//    var_dump($this->columnsConfig);die;
    
    return $this->columnsConfig;
  }
  
  
//  /**
//   * Get action
//   * 
//   * @return string
//   */
//  public function getColumnConfigAction()
//  {
//    return $this->columnConfigAction;
//  }  
  
  
  /**
   * Fill columnsConfig array with empty ullColumnConfigurations for each
   * columnConfigColumn
   * 
   * @return none
   */
  protected function createColumnsConfig()
  {
    $this->columnsConfig = new ullColumnConfigCollection;
    
    foreach ($this->getColumnConfigColumns() as $columnName => $column)
    {
      $this->columnsConfig[$columnName] = new ullColumnConfiguration;
    }
  }
  
  
  /**
   * Get a list of columns which should be used for columnConfig
   * 
   * There can be additional columns to the ones of the model
   * Example: translations, virtual columns of ullFlow, ...
   * 
   * This method is intended to be overwritten by child classes if necessary
   * 
   * @return array
   */  
  protected function getColumnConfigColumns()
  {
    if (!$this->columnConfigColumns)
    {
      $this->columnConfigColumns = $this->getColumns();
    }
    
    return $this->columnConfigColumns;
  }
  
  
  protected function applyCommonColumnConfigSettings()
  {
    foreach ($this->columnsConfig as $columnName => $columnConfig)
    {
      $columnConfig->setColumnName($columnName);
      $columnConfig->setAccess($this->getDefaultAccessByAction());
      //Humanize label and try to translate
//      $columnConfig->setLabel(__(sfInflector::humanize($columnName), null, 'common'));
      $columnConfig->setLabel(ullHumanizer::humanizeAndTranslateColumnName($columnName));
    }
    
    // TODO: set to disabled?
    $this->removeBlacklistColumns();

    $this->setReadOnlyColumns();

    $this->sortColumns();    
  }
    

  protected function applyDoctrineColumnConfigSettings()
  {
    foreach ($this->columnsConfig as $columnName => $columnConfig)
    {
      $this->applyDoctrineColumnConfigColumnSettings($columnName, $columnConfig);
      $this->applyDoctrineColumnConfigRelationSettings($columnName, $columnConfig);
    }    
  }  
  
  
  /**
   * Empty method to be overwritten by child classes
   * 
   * @return unknown_type
   */
  protected function applyCustomColumnConfigSettings()
  {
   
  }  
  
  
  /**
   * Configure columnConfig according to the settings of the doctrine table column
   * 
   * Examples: type (integer, string, ...), notnull, unique...
   * @param $columnName
   * @param $columnConfig
   * @return unknown_type
   */
  protected function applyDoctrineColumnConfigColumnSettings($columnName, $columnConfig)
  {
    $type = $this->columnConfigColumns[$columnName]['type'];
    $length = $this->columnConfigColumns[$columnName]['length'];
      
    $columnConfig->setMetaWidgetClassName(ullMetaWidget::getMetaWidgetClassName($type));
    
    if ($type == 'string')
    {
      if ($length > 255)
      {
        $columnConfig->setMetaWidgetClassName = 'ullMetaWidgetTextarea';
      }
      else
      {
        $columnConfig->setWidgetAttribute('maxlength', $length);
        $columnConfig->setValidatorOption('max_length', $length);
      }
    }  
    
//    var_dump($this->columnConfigColumns['my_email']);die;

    if (isset($this->columnConfigColumns[$columnName]['notnull']))
    {
      $columnConfig->setValidatorOption('required', true);
    }
    
    if (isset($this->columnConfigColumns[$columnName]['unique']))
    {
      $columnConfig->setUnique(true);
    }

    if (isset($this->columnConfigColumns[$columnName]['primary']))
    {
      $columnConfig->setAccess('r');
      $columnConfig->setUnique(true);
      $columnConfig->setValidatorOption('required', true);
    }    
      
  }


  /**
   * Set relation information in the columnConfig per column
   *
   * first we check for regular 'forward' relations,
   * ??? if there isn't one we try the 'backward' relations.
   * ??? example: from user to his groups via entitygroup.
   * 
   * @param $columnName
   * @param $columnConfig
   * @return unknown_type
   */      
  protected function applyDoctrineColumnConfigRelationSettings($columnName, $columnConfig)
  {
    // don't resolve relations for primary keys
    if (isset($this->columnConfigColumns[$columnName]['primary'])) // || $this->columnName != 'id'
    {
      return null;
    }

    $columnConfigRelations = $this->getColumnConfigRelations();
    
    if (isset($columnConfigRelations[$columnName]))
    {
      $columnConfigRelation = $columnConfigRelations[$columnName];
      
      $columnConfig->setRelation($columnConfigRelation);
      
      switch($columnConfigRelation['model'])
      {
        case 'UllUser': 
          $columnConfig->setMetaWidgetClassName('ullMetaWidgetUllEntity');
          $columnConfig->setOption('entity_classes', array('UllUser'));
          break;
        
        case 'UllGroup': 
          $columnConfig->setMetaWidgetClassName('ullMetaWidgetUllEntity');
          $columnConfig->setOption('entity_classes', array('UllGroup'));
          break;
        
        case 'UllEntity': 
          $columnConfig->setMetaWidgetClassName('ullMetaWidgetUllEntity');
          break;
          
        default:
          $columnConfig->setMetaWidgetClassName('ullMetaWidgetForeignKey');
      }
    }
//    else 
//    {
//      if ($columnRelationsForeign != null)
//      {
//        if (isset($columnRelationsForeign[$this->columnName]))
//        {
//          $this->metaWidgetClassName = 'ullMetaWidgetForeignKey';
//          $this->relation = $columnRelationsForeign[$this->columnName];
//          
//          //resolve second level relations for many to many relationships
//          $relations = Doctrine::getTable($columnRelationsForeign[$this->columnName]['model'])->getRelations();
//          foreach ($relations as $relation)
//          {
//            if ($relation->getLocal() == $this->columnName)
//            {
//              //var_dump('new model: ' . $relation->getClass());
//               $this->relation['model'] = $relation->getClass();
//              
//              break;
//            }
//          }
//        }
//      }
//    }
  }
      
  

  
  
  
  /**
   * Build relation information for columnConfigs
   */      
  protected function getColumnConfigRelations()
  {
    if ($this->columnConfigRelations)
    {
      return $this->columnConfigRelations;
    }

    foreach($this->getRelations() as $relation)
    {
      // take the first relation for each column and don't overwrite them later on
      if (!isset($this->columnConfigRelations[$relation->getLocal()]))
      {
        $this->columnConfigRelations[$relation->getLocal()] = array(
          'model'       => $relation->getClass(), 
          'foreign_id'  => $relation->getForeign()
        );
      }
    }

    return $this->columnConfigRelations;
  }
  
  
  /**
   * remove unwanted columns
   *
   */
  protected function removeBlacklistColumns()
  {
    foreach ($this->columnsBlacklist as $column)
    {
      if (isset($this->columnsConfig[$column]))
      {
        unset($this->columnsConfig[$column]);
      }
    }
  }

  
  /**
   * set columns which are always read only
   *
   */
  protected function setReadOnlyColumns()
  {
    foreach($this->columnsReadOnly as $column)
    {
      $this->columnsConfig[$column]->setAccess('r');
    }
  }

  
  /**
   * do some default sorting of the column order
   *
   * @deprecated use ullColumnConfigCollection::orderBottom()
   */
  protected function sortColumns()
  {
    $this->columnsConfig->orderBottom($this->columnsOrderBottom);
  }  
  
  
  /**
   * Returns an access type string according to the action
   * 
   * @return string ('r' for read / 'w' for write access)
   */
  protected function getDefaultAccessByAction()
  {
    if (in_array($this->columnConfigAction, array('create', 'edit')))
    {
      return 'w';
    }
    
    return 'r';
  }
  
  /**
   * Returns the default access  
   * 
   * @return string ('r' for read / 'w' for write access)
   */
  public function getDefaultAccess()
  {
    return $this->getDefaultAccessByAction($this->columnConfigAction);  
  }
  

}