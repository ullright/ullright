<?php

class ullMetaWidgetPriority extends ullMetaWidget
{
  
  protected function configureWriteMode()
  {
    $this->addWidget(new sfWidgetFormSelect(array('choices' => $this->getChoices())));
    
    $this->addValidator(new sfValidatorChoice(
        array_merge($this->columnConfig->getValidatorOptions(), array('choices' => array_keys($this->getChoices())))));

  }
 
  protected function configureReadMode()
  {
    $this->addWidget(new ullWidgetPriorityRead($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    $this->addValidator(new sfValidatorPass());
  }
  
  public function getSearchType()
  {
    return 'foreign';
  }
  
  protected function getChoices()
  {
    return array(
      1 => __('Very high', null, 'common'),
      2 => __('High', null, 'common'), 
      3 => __('Normal', null, 'common'), 
      4 => __('Low', null, 'common'), 
      5 => __('Very low', null, 'common')
    );
  }
}