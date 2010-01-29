<?php

/**
 * This class provides build support for ullFlow search
 * forms. It inherits ullSearchGenerator which is
 * responsible for most of the work, see the class for
 * reference.
 */
class ullFlowSearchGenerator extends ullSearchGenerator
{
  private $ullFlowApp;

  /**
   * Returns a new instance of the ullFlowSearchGenerator.
   * Optionally takes an UllFlowApp if virtual column support
   * is desired.
   * 
   * @param $searchFormEntries An array of search form entries
   * @param $baseModelName The base model name ('UllFlowDoc')
   * @param $ullFlowApp An optional UllFlowApp 
   * @return ullFlowSearchGenerator The new instance
   */
  public function __construct($searchFormEntries, $baseModelName, $ullFlowApp)
  {
    $this->ullFlowApp = $ullFlowApp;
    parent::__construct($searchFormEntries, $baseModelName);
  }

  /**
   * This adds code to the base customColumnConfig(), mainly
   * virtual column support.
   * 
   * @param $columnConfig The column configuration to modify
   */
  protected function customColumnConfig($columnConfig)
  {
    $columnConfig = parent::customColumnConfig($columnConfig);
    
    //if no app is set, this never happens, because we don't have
    //virtual columns
    if ($columnConfig->getCustomAttribute('searchFormEntry')->isVirtual == true)
    {
      $virtualColumnConfig = $this->ullFlowApp->findColumnConfigBySlug($columnConfig->getCustomAttribute('searchFormEntry')->columnName);
      
      //taken from ullFlowGenerator
      $columnConfig->setLabel($virtualColumnConfig->label);
      $columnConfig->setMetaWidgetClassName($virtualColumnConfig->UllColumnType->class);
      $columnConfig->setWidgetOptions(array_merge($columnConfig->getWidgetOptions(),
        sfToolkit::stringToArray($virtualColumnConfig->options)));
      if ($virtualColumnConfig->default_value)
      {
        $columnConfig->setDefaultValue($virtualColumnConfig->default_value);
      }

      return $columnConfig;
    }
    
    //manually handle the all-searching priority widget
    if (($columnConfig->getLabel() == 'Priority') && ($columnConfig->getMetaWidgetClassName() == 'ullMetaWidgetInteger'))
    {
      $columnConfig->setMetaWidgetClassName('ullMetaWidgetPriority');
      return $columnConfig;
    }
    
    return $columnConfig;
  }
}