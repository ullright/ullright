<?php

/**
 * Empty class to be overridden by customer's class in app/...
 * 
 *
 */
class UllNewsletterLayoutColumnConfigCollection extends BaseUllNewsletterLayoutColumnConfigCollection
{
  
  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    parent::applyCustomSettings();
    
    /* If you prefere a texterea instead of the rich text editor */
//     $this['html_body']
//       ->setMetaWidgetClassName('ullMetaWidgetTextarea')
//       ->setWidgetAttribute('cols', 80)
//       ->setWidgetAttribute('rows', 20)
//     ;
           
  }
    
}