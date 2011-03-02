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

    $this['submitted_by_ull_user_id']
      ->setLabel(__('Sender', null, 'ullMailMessages'))
      ->setAccess('r')
    ;
    
    $this['submitted_at']
      ->setLabel(__('Sent at', null, 'ullMailMessages'))
      ->setAccess('r')
    ;
    
    $this['num_total_recipients']
      ->setLabel(__('Total recipient count', null, 'ullMailMessages'))
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
    
    $this['num_sent_recipients']
      ->setLabel(__('Delivered', null, 'ullMailMessages'))
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
        'num_total_recipients',
        'num_sent_recipients',
        'num_failed_emails',
        'num_read_emails',
        'submitted_by_ull_user_id',
        'submitted_at',
      ),
      'misc' => array(
        'id',
        'creator_user_id',
        'created_at',
        'updator_user_id',
        'updated_at',
      ),
      
    ));      
    
    /* we'd like to hide these, but atm this is not possible
     * since the widgets do not have a span around them
     * and no span -> no class -> not selectable
    $this->markAsAdvancedFields(array(
      'id',
      'creator_user_id',
      'created_at',
      'updator_user_id',
      'updated_at',
    ));
    */
    
    if ($this->isCreateAction())
    {
      $this->disable(array(
        'submitted_by_ull_user_id',
        'submitted_at',
        'num_total_recipients', 
        'num_failed_emails',
        'num_read_emails',
        'num_sent_recipients'
      ));
    }
  }
}