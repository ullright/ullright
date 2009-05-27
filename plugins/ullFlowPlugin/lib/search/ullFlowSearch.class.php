<?php

/**
 * This class provides virtual column support for
 * ullFlow applications to the ullSearch framework.
 */
class ullFlowSearch extends ullSearch
{
  private $ullFlowApp;

  public function __construct($ullFlowApp)
  {
    $this->ullFlowApp = $ullFlowApp;
    parent::__construct();
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

      $q->leftJoin('x.UllFlowValues ' . $uniqueAlias . ' WITH ' .
      $uniqueAlias . '.ull_flow_column_config_id = ?', $columnConfigId);
      return $uniqueAlias;
    }

    return $alias;
  }
}