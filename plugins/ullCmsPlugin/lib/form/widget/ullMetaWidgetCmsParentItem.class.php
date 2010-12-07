<?php

/**
 * Handle the select box for ullCms "parent item selection"
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class ullMetaWidgetCmsParentItem extends ullMetaWidgetForeignKey
{
    
  protected function configureWriteMode()
  {
    $this->columnConfig->removeWidgetOption('model');

    $choices = array();
    
    if ($this->columnConfig->getWidgetOption('add_empty'))
    {
      $choices[''] = '';
      $this->columnConfig->removeWidgetOption('add_empty');
    }
    
    foreach(UllCmsItemTable::getRootNodeSlugs() as $slug)
    {
      $navigation = UllCmsItemTable::getMenuTree($slug);
      $renderer = new ullTreeMenuSelectRenderer($navigation);
      $choices += $renderer->render();      
    }

//    var_dump($choices);
    $this->columnConfig->setWidgetOption('choices', $choices);
    $this->columnConfig->setValidatorOption('choices', array_keys($choices));
    
    $this->addWidget(new sfWidgetFormSelect($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    $this->addValidator(new sfValidatorChoice($this->columnConfig->getValidatorOptions()));
    
    $this->handleAllowCreate();
  }
  
}