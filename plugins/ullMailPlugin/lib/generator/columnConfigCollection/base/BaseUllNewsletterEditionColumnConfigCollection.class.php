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
      ->setHelp(
        __('[SHIFT %arrow%] - [ENTER] creates single line breaks', 
          array('%arrow%' => '&uArr;'), 'ullCoreMessages')
        . " \n" .
        __('Every user database field can be used as placeholder for personalization. 
Examples: [FIRST_NAME], [LAST_NAME], [TITLE], ...', null, 'ullMailMessages'))       
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
      ->setLabel(__('Sent', null, 'ullMailMessages'))
      ->setAccess('r')
    ;

    $this->useManyToManyRelation('UllNewsletterMailingLists');
    $this['UllNewsletterMailingLists']
      ->setLabel(__('Mailing lists', null, 'ullMailMessages'))
    ;
    
    $this->order(array(
      'basics' => array(
        'UllNewsletterMailingLists',
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
      
      $this['ull_newsletter_layout_id']
        ->setWidgetOption('default', PluginUllNewsletterLayoutTable::getDefaultId());
      ;
      
      $this['UllNewsletterMailingLists']
        ->setWidgetOption('default', PluginUllNewsletterMailingListTable::getDefaultIds());
      ;
    }
    
    
    if ($this->isListAction())
    {
      $this['num_sent_recipients']
        ->setMetaWidgetClassName('ullMetaWidgetLink')
      ;
      
      $this['num_read_emails']
        ->setMetaWidgetClassName('ullMetaWidgetLink')
      ;

      $this['num_failed_emails']
        ->setMetaWidgetClassName('ullMetaWidgetLink')
      ;      
    }    
    
  }
}