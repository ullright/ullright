<?php
/**
 * TableConfiguration for UllFlowAction
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class UllFlowActionTableConfiguration extends ullTableConfiguration
{
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
    $this->setName(__('Actions', null, 'ullFlowMessages'));
    $this->setSearchColumns(array('slug'));
    $this->setSortColumns('slug');
  }
}