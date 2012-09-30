<?php

class TestTableColumnConfigCollection extends ullColumnConfigCollection
{
  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    $this['my_string']->
      setLabel(__('My custom string label', null, 'ullCoreMessages'))
    ;
    $this['my_email']
      ->setMetaWidgetClassName('ullMetaWidgetEmail')
    ;
    $this['my_select_box']
      ->setMetaWidgetClassName('ullMetaWidgetUllSelect')
      ->setWidgetOption('ull_select', 'my-test-select-box')
      ->setWidgetOption('add_empty', true)
    ;
    
    $this['my_gallery']
      ->setMetaWidgetClassName('ullMetaWidgetGallery')
      ->setOption('image_width', 670)
      ->setOption('image_height', 447)
      ->setOption('create_thumbnails', false)
//       ->setOption('single', true)
    ;
    
    
    
//     $this['my_content_elements']
//       ->setMetaWidgetClassName('ullMetaWidgetContentElements')
//       ->setWidgetOption('element_types', array(
//         'text_with_image' => __('Text with image'),
//         'gallery'         => __('Gallery'),
//       ))
//     ;       
    
    $this->disable(array(
      'my_useless_column'
    ));
  }
}