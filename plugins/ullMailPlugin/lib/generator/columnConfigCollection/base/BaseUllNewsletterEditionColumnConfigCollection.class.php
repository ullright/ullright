<?php

class BaseUllNewsletterEditionColumnConfigCollection extends ullColumnConfigCollection
{
  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    $this->disable(array(
      'sender_culture',
      'queued_at',
    ));
    
    $this['ull_newsletter_layout_id']
      ->setLabel(__('Layout', null, 'ullMailMessages'))
      ->setWidgetOption('add_empty', true)
    ;
    
    $this['body']
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

    $this->useManyToManyRelation('UllNewsletterEditionMailingLists');
    $this['UllNewsletterEditionMailingLists']
      ->setLabel(__('Mailing lists', null, 'ullMailMessages'))
    ;
    
    $this->order(array(
      'basics' => array(
        'UllNewsletterEditionMailingLists',
        'subject',
        'body',
        'ull_newsletter_layout_id',
        'is_active',
      ),
      'tracking' => array(
        'num_sent_emails', 
        'num_failed_emails',
        'num_read_emails',
        'sent_by_ull_user_id',
        'sent_at',
      ),
      'misc' => array(
        'id',
        'creator_user_id',
        'created_at',
        'updator_user_id',
        'updated_at',
      ),
      
    ));      
    
    $this->markAsAdvancedFields(array(
      'id',
      'creator_user_id',
      'created_at',
      'updator_user_id',
      'updated_at',    
    ));
    
    if ($this->isCreateAction())
    {
      $this->disable(array(
        'sent_by_ull_user_id',
        'sent_at',
        'num_sent_emails', 
        'num_failed_emails',
        'num_read_emails',
      ));
    }
  }
}