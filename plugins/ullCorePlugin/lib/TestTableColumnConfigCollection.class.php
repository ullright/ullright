<?php 

class TestTableColumnConfigCollection extends ullColumnConfigCollection
{
  
  protected function applyCustomSettings()
  {
    $this['my_select_box']->setLabel('My custom select box label');
  }     
  
}