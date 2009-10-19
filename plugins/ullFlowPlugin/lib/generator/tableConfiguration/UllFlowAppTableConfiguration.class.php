<?php
/**
 * TableConfiguration for UllFlowApp
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class UllFlowAppTableConfiguration extends ullTableConfiguration
{
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
    $this->setName(__('Workflows', null, 'ullFlowMessages'));
    $this->setSearchColumns(array('slug'));
    $this->setOrderBy('slug');
  }
  
}