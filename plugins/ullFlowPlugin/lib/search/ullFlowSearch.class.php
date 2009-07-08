<?php

/**
 * This class provides virtual column support for
 * ullFlow applications to the ullSearch framework.
 */
class ullFlowSearch extends ullSearch
{
  protected $ullFlowApp;
  protected $ullFlowAppId;
  
  /**
   * Initializes a new ullFlowSearch.
   *
   * Optionally takes an ullFlowApp object representing the application
   * this search should provide virtual column support for.
   *
   * @param $ullFlowApp an ullFlowApp
   * @return a new ullFlowSearch object
   */
  public function __construct($ullFlowApp = null)
  {
    $this->ullFlowApp = $ullFlowApp;
    $this->ullFlowAppId = $this->ullFlowApp->id;
    parent::__construct();
  }

  /**
   * This function is called before serialization and ensures that
   * the ullFlowApp instance is not stored, only the id.
   * 
   * @return array with the fields to serialize
   */
  public function __sleep()
  {
    return array_merge(array('ullFlowAppId'), parent::__sleep());
  }
  
  /*
   * This function is called after deserialization and reloades
   * the ullFlowApp instance from the stored id.
   */
  public function __wakeup()
  {
    $this->ullFlowApp = Doctrine::getTable('UllFlowApp')->findById($this->ullFlowAppId);
    //if the app is not retrievable, remove any search criteria for safety
    if ($this->ullFlowApp == null)
    {
      $this->criterionGroups = array();
    }
  }
  
  /**
   * This function overrides the base implementation and provides support
   * for virtual columns.
   *
   * It changes virtual column names to 'virtual'.
   *
   * @param $columnName The current column name
   * @return The modified column name
   */
  protected function modifyColumnName($columnName)
  {
    return (strpos($columnName, 'isVirtual.') === 0) ? 'value' : $columnName;
  }
  
  /**
   * This function overrides the base implementation and provides support
   * for virtual columns.
   *
   * It adds a join to the current query, adding the virtual values table.
   * Then it modifies the current alias to reference to the correct column
   * value.
   *
   * @param $q The current doctrine query
   * @param $alias The current alias
   * @param $criterion The current search criterion
   * @return the modified alias
   */
  protected function modifyAlias(Doctrine_Query $q, $alias, ullSearchCriterion $criterion)
  {
    if ($this->ullFlowApp === null)
    {
      //we can't do virtual columns if we're not searching a specific app
      return $alias;
    }

    $columnName = $criterion->columnName;
    if (strpos($columnName, 'isVirtual.') === 0)
    {
      $columnName = substr($columnName, 10);
      $columnConfigId = $this->ullFlowApp->findColumnConfigBySlug($columnName)->id;

      $uniqueAlias = str_replace('.', '', uniqid(''));
      
      $q->leftJoin($alias . '.UllFlowValues ' . $uniqueAlias . ' WITH ' .
      $uniqueAlias . '.ull_flow_column_config_id = ?', $columnConfigId);
      return $uniqueAlias;
    }

    return $alias;
  }
}