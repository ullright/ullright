<?php

class BaseUllNewsletterEditionColumnConfigCollection extends ullColumnConfigCollection
{
  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
//    $this['ull_newsletter_layout_id']
//      ->setLabel(__('Layout', null, 'ullMailMessages'))
//    ;
    
    $this['html_body_template']
      ->setLabel(__('Text', null, 'common'))
    ;
    
    $this['sent_at']
      ->setLabel(__('Sent at', null, 'ullMailMessages'))
    ;
    
    $this['num_sent_emails']
      ->setLabel(__('No. of emails sent', null, 'ullMailMessages'))
      ->setAccess('r')
    ;
    
    $this['num_read_emails']
      ->setLabel(__('No. of emails read', null, 'ullMailMessages'))
      ->setAccess('r')
    ;    
    
      $q = new Doctrine_Query;
      $q
        ->select('name')
        ->from('UllNewsletterMailingList')
      ;
      //needed for performance reasons when displaying all users
      $q->setHydrationMode(Doctrine::HYDRATE_ARRAY);
      
      $this->create('UllNewsletterEditionMailingLists')
        ->setMetaWidgetClassName('ullMetaWidgetManyToMany')
        //set model (it's a required option)
        ->setWidgetOption('model', 'UllNewsletterMailingList')
        ->setWidgetOption('query', $q)
        //see ullWidgetManyToManyWrite class doc for why we set this
        ->setWidgetOption('key_method', 'id')
        ->setWidgetOption('method', 'name')
        ->setValidatorOption('model', 'UllNewsletterMailingList')
        ->setValidatorOption('query', $q)
      ;    
    
    if ($this->isCreateAction())
    {
      $this->disable(array(
        'sent_by_ull_user_id',
        'sent_at',
        'num_sent_emails', 
        'num_read_emails'));
    }
    
    
    
    
//    if ($this->isListAction())
//    {
//      $this->disableAllExcept(array('id', 'title', 'link_name', 'link_url', 'activation_date', 'deactivation_date'));
//    }
  }
}