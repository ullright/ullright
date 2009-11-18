<?php 

class UllCloneUserColumnConfigCollection extends UllUserColumnConfigCollection
{
  
  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    parent::applyCustomSettings();
    
    $cloneUserColumns = sfConfig::get('app_ull_user_clone_user_columns', array(
      'ull_company_id', 
      'ull_location_id', 
      'ull_department_id', 
      'ull_job_title_id', 
      'superior_ull_user_id', 
      'ull_user_status_id', 
      'comment')
    );
    
    $cloneUserColumns = array_merge($cloneUserColumns, $this->showOnlyInEditModeAndReadOnly);
    
    $this->disableAllExcept($cloneUserColumns);
    
    $this['parent_ull_user_id']
      ->setLabel(__('Original user', null, 'ullCoreMessages'))
      ->setOption('show_search_box', true)
      ->setWidgetOption('add_empty', true)
      ->setValidatorOption('required', true)
      ->setShowSpacerAfter(true)
    ;
    
    $this->enable(array('parent_ull_user_id'));

    $this->order(array('id', 'parent_ull_user_id'));
  }
  
}