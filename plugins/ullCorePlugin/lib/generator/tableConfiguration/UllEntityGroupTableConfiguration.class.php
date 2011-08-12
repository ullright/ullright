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
    $this->setListColumns(array(
      'UllUser->display_name',
      'UllGroup->display_name',
    ));    
    $this->setSearchColumns(array(
      'UllGroup->display_name', 
      'UllEntity->display_name',
    ));
    $this->setOrderBy('UllGroup->display_name, UllUser->display_name');
//    $this->setOrderBy('ull_group_id');
    

//    $this->setSearchColumns(array(
//      'display_name', 
//      'email'
//    ));
    $this->setFilterColumns(array(
      'ull_group_id' => '_all_', 
//      'ull_entity_id' => '_all_',
    ));
    
    
  }
  
}