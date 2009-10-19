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
    $this->setSearchColumns(array('ull_entity_id', 'ull_group_id'));
    $this->setOrderBy('ull_group_id');
  }
  
}