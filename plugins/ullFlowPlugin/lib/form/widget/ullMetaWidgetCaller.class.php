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
    
    parent::configureWriteMode();
  }
}