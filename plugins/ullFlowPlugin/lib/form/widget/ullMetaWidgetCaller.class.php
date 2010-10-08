<?php
/**
 * ullMetaWidgetCaller
 *
 *
 * todo available options:
 * todo  'ull_select' => string, slug of a UllSelect
 * todo  'add_empty'  => boolean, true to include an empty entry
 */
class ullMetaWidgetCaller extends ullMetaWidgetUllEntity
{
  protected 
    $writeWidget = 'ullWidgetCaller'
  ; 
  
  protected function configureWriteMode()
  {
    $this->columnConfig->setOption('show_search_box', true);
    $this->columnConfig->setOption('add_empty', true);
    $this->columnConfig->setOption('entity_classes', array('UllUser'));
    
    parent::configureWriteMode();
  }
}