<?php 

class ullTestTableColumnConfigCollection extends ullColumnConfigCollection
{

  public static function build($action = 'edit')
  {
    $c = new self('TestTable', $action);
    $c->buildCollection();
    
    return $c;
  }
  
  protected function applyCustomSettings()
  {
    $this['my_select_box']->setLabel('My custom select box label');
  }     
  
}