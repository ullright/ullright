<?php

/**
 * Handle foreign key fields
 * 
 * @author klemens.ullmann-marx@ull.at
 *
 */
class ullMetaWidgetForeignKey extends ullMetaWidget
{
  protected function configure()
  {
    if (!$this->columnConfig->getWidgetOption('model'))
    {
      $relation = $this->columnConfig->getRelation();
      $this->columnConfig->setWidgetOption('model', $relation['model']);
    }    
  }
    
  protected function configureWriteMode()
  {
    $this->parseOptions();
    
    $this->addWidget(new ullWidgetFormDoctrineChoice($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    $this->addValidator(new sfValidatorDoctrineChoice($this->columnConfig->getValidatorOptions()));
    
    $this->handleAllowCreate();
  }
  
  
  protected function configureReadMode()
  {
    //ullWidgetForeignKey doesn't support option 'add_empty'
    $this->columnConfig->removeWidgetOption('add_empty');
    
    $this->addWidget(new ullWidgetForeignKey($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    $this->addValidator(new sfValidatorPass());
  }

  public function getSearchType()
  {
    return 'foreign';
  }
  
  
  /**
   * Parse options
   * 
   * Only for write mode
   * 
   * @return unknown_type
   */
  protected function parseOptions()
  {
    if ($this->columnConfig->getWidgetOption('model'))
    {
      $this->columnConfig->setValidatorOption('model', $this->columnConfig->getWidgetOption('model'));
    }
    else
    {
      $relation = $this->columnConfig->getRelation();
      $this->columnConfig->setValidatorOption('model', $relation['model']);
    }
    
    
    // the dropdown field can't be mandatory in case of allowCreate
    if ($this->columnConfig->getAllowCreate() == true)
    {
      $this->columnConfig->setValidatorOption('required', false);
    }
    
    if ($this->columnConfig->getOption('show_search_box'))
    {
      $this->columnConfig->setWidgetOption('show_search_box', true);
    }
    else
    {
      $this->columnConfig->setWidgetOption('show_search_box', false);
    }        
    
    if ($this->columnConfig->getOption('enable_inline_editing'))
    {
      $this->columnConfig->setWidgetOption('enable_inline_editing', true);
    }
    else
    {
      $this->columnConfig->setWidgetOption('enable_inline_editing', false);
    }       
  }
  
  
  /**
   * Handle allow create
   * @return unknown_type
   */
  protected function handleAllowCreate()
  {
    if ($this->columnConfig->getAllowCreate() == true)
    {
      $this->columnConfig->removeWidgetOption('add_empty');
      $this->columnConfig->removeWidgetOption('model');
      $this->columnConfig->removeWidgetOption('query');
      $this->columnConfig->removeWidgetOption('show_search_box');
      $this->columnConfig->removeWidgetOption('enable_inline_editing');
      $this->columnConfig->removeValidatorOption('model');
      $createColumnName = $this->columnName . '_create';
      $this->addWidget(new sfWidgetFormInput($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()), $createColumnName);
      $this->addValidator(new sfValidatorString($this->columnConfig->getValidatorOptions()), $createColumnName);
      $this->form->getWidgetSchema()->moveField($createColumnName, 'after', $this->columnName);
      
      // add a "one of the two fields is mandatory" validator
      $this->form->mergePostValidator(new sfValidatorOr(array(
        new sfValidatorSchemaFilter(
          $this->columnName, 
          new sfValidatorString(array('required' => true), array('required' => __('Please select a value or enter a new one', null, 'common')))),
        new sfValidatorSchemaFilter(
          $createColumnName, 
          new sfValidatorString(array('required' => true), array('required' => __('Please select a value or enter a new one', null, 'common')))),
      ))); 
    }    
  }
}