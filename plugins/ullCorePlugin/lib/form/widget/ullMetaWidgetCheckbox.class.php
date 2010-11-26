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
    $ajaxConfiguration = $this->getColumnConfig()->getOption('configure_for_ajax');
    if (is_array($ajaxConfiguration))
    {
      $this->addWidget(new ullWidgetAjaxCheckbox(
        array_merge($this->columnConfig->getWidgetOptions(), $ajaxConfiguration),
        $this->columnConfig->getWidgetAttributes())
      );
    }
    else
    {
      if ($this->getColumnConfig()->getOption('enable_ajax_update'))
      {
        $ajaxOptions = array(
          'enable_ajax_update', 
          'ajax_url', 
          'ajax_model', 
          'ajax_column'
        );
        
        foreach ($ajaxOptions as $option)
        {
          $this->getColumnConfig()->setWidgetOption($option, $this->getColumnConfig()->getOption($option));
        }
      }
      
      $this->addWidget(new ullWidgetCheckbox($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    }
    $this->addValidator(new sfValidatorPass());     
  }
  
  protected function configureWriteMode()
  {
    $this->addWidget(new ullWidgetCheckboxWrite($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    $this->addValidator(new sfValidatorBoolean($this->columnConfig->getValidatorOptions()));
  }
  
  protected function configureSearchMode()
  {
    // _all_ is used because we cannot use null (or empty string) because it is removed by reqpass
    $choices = array('_all_' => __('All', null, 'common'), 'checked' => __('Yes', null, 'common'), 'unchecked' => __('No', null, 'common'));
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
