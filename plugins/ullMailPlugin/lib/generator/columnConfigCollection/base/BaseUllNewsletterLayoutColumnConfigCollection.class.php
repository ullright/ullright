<?php

class BaseUllNewsletterLayoutColumnConfigCollection extends ullColumnConfigCollection
{
  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    $this['html_body']
      ->setLabel(__('HTML body', null, 'ullMailMessages'))
      ->setMetaWidgetClassName('ullMetaWidgetFCKEditor')
    ;
    $this['html_head']
      ->setLabel(__('HTML head', null, 'ullMailMessages'))
    ;
  }
}