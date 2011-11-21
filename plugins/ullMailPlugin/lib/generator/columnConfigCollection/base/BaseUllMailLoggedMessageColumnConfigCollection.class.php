<?php

class BaseUllMailLoggedMessageColumnConfigCollection extends ullColumnConfigCollection
{
  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    foreach($this->getCollection() as $item)
    {
      $item->setAccess('r');
    }
    
    $this->disable(array(
      'namespace',
      'transport_sent_status'
    ));
    
    $this['sender']
      ->setLabel(__('Sender', null, 'ullMailMessages'))
      ->setWidgetOption('decode_mime', true)
    ;
    
    $this['main_recipient_ull_user_id']
      ->setLabel(__('Recipient', null, 'ullMailMessages'))
    ;
    
    $this['to_list']
      ->setLabel(__('To', null, 'ullMailMessages'))
      ->setWidgetOption('decode_mime', true)
    ;

    $this['cc_list']
      ->setLabel(__('Cc', null, 'ullMailMessages'))
    ;

    $this['bcc_list']
      ->setLabel(__('Bcc', null, 'ullMailMessages'))
    ;    
    
    $this['headers']
      ->setLabel(__('Headers', null, 'ullMailMessages'))
    ;
    
    $this['subject']
      ->setWidgetOption('decode_mime', true)
    ;
    
    $this['plaintext_body']
      ->setLabel(__('Plaintext body', null, 'ullMailMessages'))
    ;
    
    $this['html_body']
      ->setLabel(__('Html body', null, 'ullMailMessages'))
      ->setMetaWidgetClassName('ullMetaWidgetFCKEditor')
    ;    
    
    $this['first_read_at']
      ->setLabel(__('First read at', null, 'ullMailMessages'))
    ;
    
    $this['num_of_readings']
      ->setLabel(__('Read counter', null, 'ullMailMessages'))
    ;    
    
    $this['last_ip']
      ->setLabel(__('Last used IP-address', null, 'ullMailMessages'))
    ;    
    
    $this['last_user_agent']
      ->setLabel(__('Last used application', null, 'ullMailMessages'))
    ;
    
    $this['sent_at']
      ->setLabel(__('Sent at', null, 'ullMailMessages'))
    ;    
    
    $this['ull_newsletter_edition_id']
      ->setLabel(__('Newsletter', null, 'ullMailMessages'))
    ;    

    $this['failed_at']
      ->setLabel(__('Failed at', null, 'ullMailMessages'))
    ;        
    
    $this['ull_mail_error_id']
      ->setLabel(__('Error', null, 'ullMailMessages'))
    ;     
    
    $this['last_error_message']
      ->setLabel(__('Last error message', null, 'ullMailMessages'))
      ->setWidgetOption('decode_mime', true)
    ;       
    
    $this->order(array(
      'from-to' => array(
        'sender',
        'main_recipient_ull_user_id',
        'to_list',
        'cc_list',
        'bcc_list',
      ),
      'stats' => array(
        'sent_at'
       ),
      'main' => array(
        'ull_newsletter_edition_id',
        'subject',
        'html_body',
        'plaintext_body',
      ),
      'tracking' => array (
        'first_read_at',
        'num_of_readings',
        'last_ip',
        'last_user_agent',
      ),
      'error' => array(
        'failed_at',
        'ull_mail_error_id',
        'last_error_message',
      ),
    ));
    

    
  }
}