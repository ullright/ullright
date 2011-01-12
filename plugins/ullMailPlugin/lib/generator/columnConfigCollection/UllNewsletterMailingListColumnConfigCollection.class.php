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
    ;   
  }
}