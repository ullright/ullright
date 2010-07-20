<?php
/**
 * TableConfiguration for UllEntityGroup
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class UllEntityGroupTableConfiguration extends ullTableConfiguration
{
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
    $this->setName(__('Group memberships', null, 'ullCoreMessages'));
    $this->setSearchColumns(array('UllGroup->display_name', 'UllEntity->display_name'));
    $this->setOrderBy('ull_group_id');
  }
  
}