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
    if ($option = $this->columnConfig->getWidgetOption('entity_classes'))
    {
      if (!is_array($option))
      {
        $option = array($option);        
      }
      $this->columnConfig->setOption('entity_classes', $option);
      $this->columnConfig->removeWidgetOption('entity_classes');
    }
    else
    {
      $this->columnConfig->setOption('entity_classes', array('UllUser'));
    }
    
    $this->columnConfig->setWidgetOption('add_empty', true);
    
    parent::configureWriteMode();
  }
}