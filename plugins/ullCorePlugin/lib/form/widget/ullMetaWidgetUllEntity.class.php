<?php

/**
 * ullMetaWidgetUllEntity
 *
 *
 * available options:
 * 'add_empty'  => boolean, true to include an empty entry
 * 'entity_classes'  => array, list of UllEntity classes to include in the option list
 * 'show_search_box' => boolean, show js search box to filter the select box entries (default = yes)
 */
class ullMetaWidgetUllEntity extends ullMetaWidget
{

  protected function configureReadMode()
  {
    $this->addWidget(new ullWidgetForeignKey(array('model' => 'UllEntity')));
    $this->addValidator(new sfValidatorPass());
  }
  
  protected function configureWriteMode()
  {
    // set default entity class
    if (!($this->columnConfig->getOption('entity_classes')))
    {
      $this->columnConfig->setOption('entity_classes', array('UllUser', 'UllGroup'));
    }
    
    if ($this->columnConfig->getWidgetOption('add_empty'))
    {
      $choices = array('' => array('name' => ''));
    }
    else
    {
      $choices = array();
    }
    $this->columnConfig->removeWidgetOption('add_empty');
    
    // build choices
    foreach($this->columnConfig->getOption('entity_classes') as $class)
    {
      $className = $class . 'Table';
      
      if (method_exists($className, 'findChoices'))
      {
        $choices += call_user_func(array($className, 'findChoices'));
      }
      else
      {
        throw new InvalidArgumentException('The given entity class has no "getChoices()" method implemented: ' . $class);
      }
    }
    
 
    if ($this->columnConfig->getOption('show_search_box'))
    {
      $this->columnConfig->setWidgetOption('show_search_box', true);
    }
    else
    {
      $this->columnConfig->setWidgetOption('show_search_box', false);
    }
    
    $this->addWidget(new sfWidgetFormSelectWithOptionAttributes(
        array_merge(array('choices' => $choices), $this->columnConfig->getWidgetOptions()),
        $this->columnConfig->getWidgetAttributes()
    ));
    
    $this->addValidator(new sfValidatorChoice(
        array_merge(array('choices' => array_keys($choices)), $this->columnConfig->getValidatorOptions()))
    );
  }
  
  public function getSearchType()
  {
    return 'foreign';
  }
  
}