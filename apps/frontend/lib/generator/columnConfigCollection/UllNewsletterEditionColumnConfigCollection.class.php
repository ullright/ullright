<?php

/**
 * Empty class to be overridden by customer's class in app/...
 * 
 *
 */
class UllNewsLetterEditionColumnConfigCollection extends BaseUllNewsletterEditionColumnConfigCollection
{
  
  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    parent::applyCustomSettings();
    
    /* @see http://www.ullright.org/ullWiki/show/content-elements for details */
    
//     $this['body']
//       ->setMetaWidgetClassName('ullMetaWidgetContentElements')
//       ->setWidgetOption('element_types', array(
//         'text_with_image' => array(
//           'label' => __('Text with image'),
//         ),
//         'gallery' => array(
//           'label' => __('Gallery'),
//         ),
//       ))
//       ->setWidgetOption('css_class', 'newsletter_example_layout')
//       ->setWidgetOption('stylesheet', '/css/ull_newsletter_layout_example_layout_prefixed.css')
//     ;
           
  }
  
}