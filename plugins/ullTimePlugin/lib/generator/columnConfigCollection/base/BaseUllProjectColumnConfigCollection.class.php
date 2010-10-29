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
      $q = new Doctrine_Query;
      $q
        ->select('display_name')
        ->from('UllUser')
      ;
      //needed for performance reasons when displaying all users
      $q->setHydrationMode(Doctrine::HYDRATE_ARRAY);
      
      $this->create('Managers')
        ->setMetaWidgetClassName('ullMetaWidgetManyToMany')
        //set model (it's a required option)
        ->setWidgetOption('model', 'UllUser')
        ->setWidgetOption('query', $q)
        //see ullWidgetManyToManyWrite class doc for why we set this
        ->setWidgetOption('key_method', 'id')
        ->setWidgetOption('method', 'display_name')
        ->setValidatorOption('model', 'UllUser')
        ->setValidatorOption('query', $q)
      ;
      
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