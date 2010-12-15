<?php

class BaseUllNewsletterLayoutColumnConfigCollection extends ullColumnConfigCollection
{
  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    $this['html_layout']
      ->setLabel(__('HTML layout', null, 'ullMailMessages'))
      ->setMetaWidgetClassName('ullMetaWidgetFCKEditor')
    ;
  }
}