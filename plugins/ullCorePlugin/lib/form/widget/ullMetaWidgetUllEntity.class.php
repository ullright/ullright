<?php

/**
 * ullMetaWidgetUllEntity
 *
 * available options (given by columnConfig->setOption)
 *  'enable_ajax_autocomplete' boolean, default=false, enable ajax autocomplete instead of select box
 *  'entity_classes'           array, list of UllEntity classes to include in the option list
 *                             default = UllUser, UllGroup 
 *  'enable_inline_editing'    boolean, only available when supplying a single entity class 
 *  'filter_users_by_group'    string, show only members with the given UllGroup name
 * 
 *  'show_search_box'          boolean, show js search box to filter the select box entries (default = yes)  
 *
 * available generic widget options (given by columnConfig->setWidgetOption)
 *  'add_empty'                boolean, true to include an empty entry
 *                             only used for classic select box mode


 */
class ullMetaWidgetUllEntity extends ullMetaWidget
{
  protected
    $readWidget = 'ullWidgetForeignKey',
    $writeWidget = 'ullWidgetFormChoiceUllEntity',
    $writeAjaxWidget = 'ullWidgetUllEntityAjaxWrite',
    $validator = 'ullValidatorUllEntity'
  ;

  /**
   * Configure the read mode
   * 
   * @see plugins/ullCorePlugin/lib/form/widget/ullMetaWidget::configureReadMode()
   */
  protected function configureReadMode()
  {
    $this->addWidget(new $this->readWidget(
      array('model' => 'UllEntity', 'show_ull_entity_popup' => true),
      $this->columnConfig->getWidgetAttributes()
    ));
    $this->addValidator(new sfValidatorPass());
  }
  
  
  /**
   * Configure the write (edit) mode
   * 
   * @see plugins/ullCorePlugin/lib/form/widget/ullMetaWidget::configureWriteMode()
   */
  protected function configureWriteMode()
  {
    $this->defaultEntityClasses();
    
    // Global mode switch between classic select box and ajax autocomplete mode
    $enableAjaxAutocomplete = sfConfig::get('app_ull_user_enable_ajax_autocomple_widget', false);
    
    // Override per widget via columnConfig
    if ($this->columnConfig->getOption('enable_ajax_autocomplete'))
    {
      $enableAjaxAutocomplete = $this->getOption('enable_ajax_autocomplete');
    }
    
    if ($enableAjaxAutocomplete)
    {
      $this->configureWriteModeAjax();
    }
    else
    {
      $this->configureWriteModeClassic();
    }
    
    $this->configureWriteValidator();
     
  }
  
  /**
   * Configure write mode with ajax autocomplete
   * 
   */
  protected function configureWriteModeAjax()
  {
    //Pass options to widget
    $this->columnConfig->setWidgetOption('entity_classes', 
      $this->columnConfig->getOption('entity_classes'));
    $this->columnConfig->setWidgetOption('hide_choices', 
      $this->columnConfig->getOption('hide_choices'));
    $this->columnConfig->setWidgetOption('filter_users_by_group', 
      $this->columnConfig->getOption('filter_users_by_group'));
      
    //Remove unsupported options
    $this->columnConfig->removeWidgetOption('add_empty');
    $this->columnConfig->removeWidgetOption('renderer_options');
    $this->columnConfig->removeWidgetOption('renderer_class');
    
    $this->addWidget(new $this->writeAjaxWidget(
      $this->columnConfig->getWidgetOptions(),
      $this->columnConfig->getWidgetAttributes()
    ));
    
  }

  /**
   * Configure write mode for classic select box
   * 
   * @throws InvalidArgumentException
   */
  protected function configureWriteModeClassic()
  {
    // Handle 'add_empty' option
    $choices = array();
    if ($this->columnConfig->getWidgetOption('add_empty'))
    {
      $choices = array('' => array('name' => ''));
    }
    $this->columnConfig->removeWidgetOption('add_empty');
    
    $filterUsersByGroup = $this->columnConfig->getOption('filter_users_by_group');
    
    // Build choices
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
    
    // Shall we hide some choices?
    if ($hideChoices = $this->columnConfig->getOption('hide_choices'))
    {
      $choices = array_diff_key($choices, array_flip($hideChoices));
    }
    
    // Limit entry label length
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

    // Handle js search box
    $this->columnConfig->setWidgetOption('show_search_box', false);
    if ($this->columnConfig->getOption('show_search_box'))
    {
      $this->columnConfig->setWidgetOption('show_search_box', true);
    }
    
    // Inline editing

    $this->columnConfig->setWidgetOption('enable_inline_editing', false);
    // ullWidgetFormDoctrineChoice demands the 'model' option
    $this->columnConfig->setWidgetOption('model', 'irrelevant');
    
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
    
    // Set widget
    $this->addWidget(new $this->writeWidget(
        array_merge(array('choices' => $choices), $this->columnConfig->getWidgetOptions()),
        $this->columnConfig->getWidgetAttributes()
    ));
    
  }
  
  /**
   * Set default entity classes if none given
   * 
   */
  protected function defaultEntityClasses()
  {
    // Set default entity classes
    if (!$this->columnConfig->getOption('entity_classes'))
    {
      $this->columnConfig->setOption('entity_classes', array('UllUser', 'UllGroup'));
    }       
  }
  
  /**
   * Configure the write mode validator
   * 
   */
  protected function configureWriteValidator()
  {
    //Pass options to validator
    $this->columnConfig->setValidatorOption('entity_classes', 
      $this->columnConfig->getOption('entity_classes'));
    $this->columnConfig->setValidatorOption('hide_choices', 
      $this->columnConfig->getOption('hide_choices'));
    $this->columnConfig->setValidatorOption('filter_users_by_group', 
      $this->columnConfig->getOption('filter_users_by_group'));    
    
    // Set validator
    $this->addValidator(new $this->validator(
        $this->columnConfig->getValidatorOptions()
    ));     
  }  
  
  public function getSearchType()
  {
    return 'foreign';
  }
  
  
}