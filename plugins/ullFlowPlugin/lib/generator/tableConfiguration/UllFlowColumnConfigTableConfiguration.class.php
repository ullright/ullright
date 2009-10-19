<?php
/**
 * TableConfiguration for UllFlowColumnConfig
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class UllFlowColumnConfigTableConfiguration extends ullTableConfiguration
{
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
    $this->setName(__('Columns', null, 'ullFlowMessages'));
    $this->setSearchColumns(array('slug'));
    $this->setOrderBy('ull_flow_app_id, sequence');
  }
  
}