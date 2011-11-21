<?php 

class BaseUllNewsletterMailingListSubscriberColumnConfigCollection extends ullColumnConfigCollection
{
  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    $this['ull_newsletter_mailing_list_id']
      ->setLabel(__('Mailing list', null, 'ullMailMessages'))
    ;
  }
}