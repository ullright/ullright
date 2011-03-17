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
      ->setWidgetOption('rows', 20)
      ->setWidgetOption('cols', 80)
    ;
    $this['is_default']
      ->setLabel(__('Is default layout', null, 'ullMailMessages'))
    ;
  }
}