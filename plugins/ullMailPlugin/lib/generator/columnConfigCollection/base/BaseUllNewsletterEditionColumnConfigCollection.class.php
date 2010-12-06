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
    
    $this->useManyToManyRelation('UllNewsletterEditionMailingLists');
    
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