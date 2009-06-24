<?php
/**
 * ullMetaWidgetCheckbox
 *
 * Used for checkboxes
 */
class ullMetaWidgetCheckbox extends ullMetaWidget
{
  protected function configureReadMode()
  {
    $this->addWidget(new ullWidgetCheckbox($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    $this->addValidator(new sfValidatorPass());     
  }
  
  protected function configureWriteMode()
  {
    $this->addWidget(new ullWidgetCheckboxWrite($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    $this->addValidator(new sfValidatorBoolean($this->columnConfig->getValidatorOptions()));
  }
  
  protected function configureSearchMode()
  {
    $choices = array('any' => '', 'checked' => __('Yes', null, 'common'), 'unchecked' => __('No', null, 'common'));
    $this->addWidget(new sfWidgetFormSelect(array_merge(array('choices' => $choices),
          $this->columnConfig->getWidgetOptions()), $this->columnConfig->getWidgetAttributes()));
    $this->addValidator(new sfValidatorChoice(array_merge(array('choices' => array_keys($choices)),
          $this->columnConfig->getValidatorOptions())));
  }
  
  public function getSearchType()
  {
    return 'boolean';
  }
}
