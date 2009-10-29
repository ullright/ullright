<?php
/**
 * TableConfiguration for UllCloneUser
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class UllCloneUserTableConfiguration extends UllEntityTableConfiguration
{
  
  /**
   * (non-PHPdoc)
   * @see plugins/ullCorePlugin/lib/ullTableConfiguration#applyCustomSettings()
   */
  protected function applyCustomSettings()
  {
    $this
      ->setName(__('Clone users', null, 'ullCoreMessages'))
      ->setDescription(__('Clone users make it possible to define multiple jobtitles / positions for one user in a company. Simply enter the values which differ. All other values are taken from the original user account.', null, 'ullCoreMessages'))
      // We can't give relation columns here, because the clone user parent value retrieval only works in object mode
      //   ullQuery doesn't know how to handle clone users
//      ->setListColumns(array('first_name', 'last_name', 'UllDepartment->name', 'UllJobTitle->name', 'Superior->display_name'))
      ->setListColumns(array('first_name', 'last_name', 'ull_department_id', 'ull_job_title_id', 'superior_ull_user_id'))
      ->setOrderBy('last_name, first_name, UllJobTitle->name')
    ;  
    
  }
  
}