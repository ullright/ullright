<?php
/**
 * TableConfiguration for UllProjectReporting
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class UllProjectReportingTableConfiguration extends ullTableConfiguration
{
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
    $this->setSearchColumns(array('UllProject->name'));
  }
  
}