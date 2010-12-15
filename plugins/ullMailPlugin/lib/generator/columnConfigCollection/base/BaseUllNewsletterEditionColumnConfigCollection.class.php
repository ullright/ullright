<?php

class BaseUllNewsletterEditionColumnConfigCollection extends ullColumnConfigCollection
{
  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    $this['ull_newsletter_layout_id']
      ->setLabel(__('Layout', null, 'ullMailMessages'))
      ->setWidgetOption('add_empty', true)
    ;
    
    $this['html_body_template']
      ->setLabel(__('Text', null, 'common'))
      ->setMetaWidgetClassName('ullMetaWidgetFCKEditor')
    ;

    $this['sent_by_ull_user_id']
      ->setLabel(__('Sender', null, 'ullMailMessages'))
      ->setAccess('r')
    ;
    
    $this['sent_at']
      ->setLabel(__('Sent at', null, 'ullMailMessages'))
      ->setAccess('r')
    ;
    
    $this['num_sent_emails']
      ->setLabel(__('Mails sent', null, 'ullMailMessages'))
      ->setAccess('r')
    ;
    
    $this['num_failed_emails']
      ->setLabel(__('Undeliverable', null, 'ullMailMessages'))
      ->setAccess('r')
    ;      
    
    $this['num_read_emails']
      ->setLabel(__('Read', null, 'ullMailMessages'))
      ->setAccess('r')
    ;

//    if ($this->isCreateOrEditAction())
//    {
      $this->useManyToManyRelation('UllNewsletterEditionMailingLists');
      $this['UllNewsletterEditionMailingLists']
        ->setLabel(__('Mailing lists', null, 'ullMailMessages'))
      ;
      
      $this->order(array(
        'UllNewsletterEditionMailingLists',
        'subject',
        'html_body_template',
        'ull_newsletter_layout_id',
        'is_active',
      ));      
//    }
    
    if ($this->isCreateAction())
    {
      $this->disable(array(
        'sent_by_ull_user_id',
        'sent_at',
        'num_sent_emails', 
        'num_failed_emails',
        'num_read_emails'));
    }
  }
}