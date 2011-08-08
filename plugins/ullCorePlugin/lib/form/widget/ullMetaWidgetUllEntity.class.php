<?php

/**
 * ullMetaWidgetUllEntity
 *
 *
 * available options given by columnConfig->getWidgetOption:
 * 'add_empty'              boolean, true to include an empty entry
 * 'show_search_box'        boolean, show js search box to filter the select box entries (default = yes)
 * 'enable_inline_editing'  boolean, only available when supplying a single entity class 
 * 'filter_users_by_group'  string, show only members of the given UllGroup name
 * 'entity_classes'         array, list of UllEntity classes to include in the option list

 */
class ullMetaWidgetUllEntity extends ullMetaWidget
{
  protected
    $readWidget = 'ullWidgetForeignKey',
//    $writeWidget = 'sfWidgetFormSelectWithOptionAttributes',
    $writeWidget = 'ullWidgetFormChoiceUllEntity',
    $validator = 'sfValidatorChoice'
  ;

  protected function configureReadMode()
  {
    $this->addWidget(new $this->readWidget(
      array('model' => 'UllEntity', 'show_ull_entity_popup' => true),
      $this->columnConfig->getWidgetAttributes()
    ));
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
    
    $filterUsersByGroup = $this->columnConfig->getOption('filter_users_by_group');
    
    // build choices
    foreach($this->columnConfig->getOption('entity_classes') as $class)
    {
      $className = $class . 'Table';
      
      if (method_exists($className, 'findChoices'))
      {
        $choices += ($class == 'UllUser' && $filterUsersByGroup !== null) ? 
         call_user_func(array($className, 'findChoices'), $filterUsersByGroup) :
         call_user_func(array($className, 'findChoices'));
      }
      else
      {
        throw new InvalidArgumentException('The given entity table class has no "findChoices()" method implemented: ' . $class);
      }
    }
    
    //shall we hide some choices?
    if ($hideChoices = $this->columnConfig->getOption('hide_choices'))
    {
      $choices = array_diff_key($choices, array_flip($hideChoices));
    }
    
    //limit entries in length
    $lengthLimit = sfConfig::get('app_ull_user_display_name_length_limit', 22);
    foreach($choices as &$choice)
    {
      $oldName = $choice['name'];
      if (strlen($oldName) > $lengthLimit)
      {
        $newName = substr($oldName, 0, $lengthLimit);
        if (substr($newName, -1) == '-')
        {
          $newName = substr($newName, 0, -1);
        }
        $choice['name'] = $newName . '.';
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
    
    // Inline editing
    if ($this->columnConfig->getOption('enable_inline_editing'))
    {
      $entityClasses = $this->columnConfig->getOption('entity_classes');
      
      if (count($entityClasses) > 1)
      {
        throw new InvalidArgumentException('option "enable_inline_editing" is only allowed for a sinlge entity_class');
      }      
      
      $this->columnConfig->setWidgetOption('enable_inline_editing', true);
      $this->columnConfig->setWidgetOption('model', reset($entityClasses));
    }
    else
    {
      $this->columnConfig->setWidgetOption('enable_inline_editing', false);
      // we need to supply a model name
      $this->columnConfig->setWidgetOption('model', 'irrelevant');
    }
    
    $this->addWidget(new $this->writeWidget(
        array_merge(array('choices' => $choices), $this->columnConfig->getWidgetOptions()),
        $this->columnConfig->getWidgetAttributes()
    ));
    
    $this->addValidator(new $this->validator(
        array_merge(array('choices' => array_keys($choices)), $this->columnConfig->getValidatorOptions()))
    );
  }
  
  public function getSearchType()
  {
    return 'foreign';
  }
  
}