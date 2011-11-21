<?php 

class BaseUllNewsletterMailingListColumnConfigCollection extends ullColumnConfigCollection
{
  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    $this['is_subscribed_by_default']
      ->setLabel(__('Subscribed by default', null, 'ullMailMessages'))
      ->setHelp(__('New users are automatically subscribed to this mailing list',
        null, 'ullMailMessages'))
    ;

    $this['is_default']
      ->setLabel(__('Default mailing list', null, 'ullMailMessages'))
      ->setHelp(__('This mailing list is automatically selected when composing a newsletter',
        null, 'ullMailMessages'))
    ;
    
    $this['is_public']
      ->setLabel(__('Is public', null, 'ullMailMessages'))
      ->setHelp(__('Anyone can see and subscribe this mailing list',
        null, 'ullMailMessages'))
    ;    

    $this->order(array(
      'id',
      'name',
      'description',
      'is_subscribed_by_default',
      'is_default',
      'is_public',
    ));    
    
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
        'is_subscribed_by_default',
        'is_default',
        'is_public',
      ));
    }
    
    if ($this->isListAction())
    {
      $this->create('link')
        ->setLabel(__('Subscribers', null, 'ullMailMessages'))
        ->setMetaWidgetClassName('ullMetaWidgetLinkNewsletterListToSubscriber')
        ->setIsArtificial(true)
        ->setInjectIdentifier(true)
      ;
          
    }
  }
}