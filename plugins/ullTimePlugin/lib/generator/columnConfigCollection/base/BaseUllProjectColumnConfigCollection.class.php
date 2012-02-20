<?php 

class BaseUllProjectColumnConfigCollection extends ullColumnConfigCollection
{

  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    if ($this->isListAction())
    {
      $this['created_at']->enable();
    }
    
    $this['is_routine']
      ->setLabel(__('Routine', null, 'ullTimeMessages'))
    ;
    
    $this['is_visible_only_for_project_manager']
      ->setLabel(__('Is visible only for project manager', null, 'ullTimeMessages'))
      ->setHelp(__('If checked other users cannot see or select this project', null, 'ullTimeMessages'))
    ;
    
    if ($this->isCreateOrEditAction())
    {
      //TODO: giving the model shouldn't be necessary.
      //It's only necessary because of a workaround (see schema)
      $this->useManyToManyRelation('Manager', 'UllUser');
      
      $this->order(array(
        'name',
        'description',
        'is_active',
        'is_routine',
        'Manager'
      ));      
    }


  }
}
