<?php

class TestTableColumnConfigCollection extends ullColumnConfigCollection
{
  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    $this['my_string']->setLabel(__('My custom string label', null, 'ullCoreMessages'));
    $this['my_email']->setMetaWidgetClassName('ullMetaWidgetEmail');
    $this['my_select_box']
      ->setMetaWidgetClassName('ullMetaWidgetUllSelect')
      ->setWidgetOption('ull_select', 'my-test-select-box')
      ->setWidgetOption('add_empty', true)
    ;
    
    $this->disable(array('my_useless_column'));
  }
}