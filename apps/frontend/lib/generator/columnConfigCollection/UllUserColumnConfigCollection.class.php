<?php 
/**
 * Custom UllUser columnsConfig
 * Extends/overrides the plugins' columnsConfig
 * 
 * Put your custom config here
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class UllUserColumnConfigCollection extends BaseUllUserColumnConfigCollection
{
  
  protected function applyCustomSettings()
  {
    parent::applyCustomSettings();
    
//    $this->disableAllExcept(array(
//      'id',
//      'last_name',
//      'first_name',
//      'username',
//      'password',
//      'UllGroup',
//      'ull_user_status_id',
//    ));
  }
}    
