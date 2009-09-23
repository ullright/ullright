<?php
/**
 * TableConfiguration for UllWikiAccessLevelAccess
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class UllWikiAccessLevelAccessTableConfiguration extends ullTableConfiguration
{
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
    $this->setName(__('Access rights', null, 'ullWikiMessages'));
    $this->setSearchColumns(array('ull_group_id'));
    $this->setSortColumns('ull_group_id');
  }
  
}