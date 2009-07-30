<?php

class TestTableColumnConfigCollection extends UllEntityColumnConfigCollection
{
  /**
   * Applies model specific custom column configuration
   * 
   */
  protected function applyCustomSettings()
  {
    //$this['my_string']->setMetaWidgetClassName('My custom string label');
    $this['my_email']->setMetaWidgetClassName('ullMetaWidgetEmail');
    $this['my_select_box']->setMetaWidgetClassName('ullMetaWidgetUllSelect')
    ->setWidgetOption('ull_select', 'ull_select_test')
    ->setWidgetOption('add_empty', true);
    
    $this->disable(array('my_useless_column'));
  }
}