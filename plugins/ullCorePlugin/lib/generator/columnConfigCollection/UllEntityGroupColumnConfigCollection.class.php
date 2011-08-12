<?php

class UllEntityGroupColumnConfigCollection extends ullColumnConfigCollection
{
  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    $this['ull_entity_id']
      ->setLabel(__('User', null, 'common'))
      ->setOption('show_search_box', true)
      ->setOption('entity_classes', array('UllUser'))
      ->setOption('enable_inline_editing', true)
      ->setWidgetOption('add_empty', true)
    ;
      
    $this['ull_group_id']
      ->setOption('show_search_box', true)
      ->setWidgetOption('add_empty', true)
    ;
  }
}