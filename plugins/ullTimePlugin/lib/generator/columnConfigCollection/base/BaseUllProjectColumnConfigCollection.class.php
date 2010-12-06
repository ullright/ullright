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
    
    $this['is_routine']->setLabel(__('Routine', null, 'ullTimeMessages'));
    
    if ($this->isCreateOrEditAction())
    {
      //TODO: giving the model shouldn't be necessary.
      //It's only necessary because of a workaround (see schema)
      $this->useManyToManyRelation('Managers', 'UllUser');
      
      $this->order(array(
        'name',
        'description',
        'is_active',
        'is_routine',
        'Managers'
      ));      
    }


  }
}
