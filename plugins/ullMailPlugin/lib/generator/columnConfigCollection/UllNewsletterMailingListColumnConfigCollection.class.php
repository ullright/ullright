<?php 

class UllNewsletterMailingListColumnConfigCollection extends ullColumnConfigCollection
{
  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    $this['is_subscribed_by_default']
      ->setLabel(__('Is subscribed by default', null, 'ullMailMessages'))
      ->setHelp(__('If checked, newly created users will subscribe to this mailing list by default',
        null, 'ullMailMessages'))
    ;

    $this['is_default']
      ->setLabel(__('Is default mailing list', null, 'ullMailMessages'))
    ;
    
    if ($this->isCreateOrEditAction())
    {
      $this->useManyToManyRelation('Subscribers');
      $this['Subscribers']
        ->setLabel(__('Subscribers', null, 'ullMailMessages'))
      ;
      
      $this->order(array(
        'id',
        'name',
        'description',
        'Subscribers',
      ));
    }
  }
}