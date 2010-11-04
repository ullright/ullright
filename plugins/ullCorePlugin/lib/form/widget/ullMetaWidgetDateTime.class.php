<?php

/**
 * This meta widget represents a date with a time component.
 * 
 * If the 'act_as_due_date' option is set to true, this widget
 * disregards the time component and adds reminder/overdue
 * color spans.
 */
class ullMetaWidgetDateTime extends ullMetaWidget
{
  protected function configureWriteMode()
  {
    if ($this->columnConfig->getWidgetAttribute('size') == null)
    {
      $this->columnConfig->setWidgetAttribute('size', '10');
    }

    $this->columnConfig->removeWidgetOption('act_as_due_date');
    
    //add fake_timestamp option to validator to create "date 00:00:00" values
    $fixedValidatorOptions = array('fake_timestamp' => true);
    
    $this->addWidget(new ullWidgetDateWrite($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    $this->addValidator(new ullValidatorDate(array_merge($fixedValidatorOptions, $this->columnConfig->getValidatorOptions())));
  }
   
  protected function configureReadMode()
  {
    if ($this->getColumnConfig()->getWidgetOption('act_as_due_date'))
    {
      $reminderDays = (int)sfConfig::get('app_ull_flow_due_date_reminder_period', 2);
      $reminderDays = ($reminderDays < 0) ? 2 : $reminderDays;
      
      $dateOverdue = strtotime('today');
      $dateReminder = strtotime('+' . $reminderDays . ' day', strtotime('today'));
      $options = array('add_span_if_before' => array(
        $dateReminder => 'ull_widget_datetime_warning',
        $dateOverdue => 'ull_widget_datetime_alert',
      ));
      
      $this->addWidget(new ullWidgetDateRead($options, $this->columnConfig->getWidgetAttributes()));
    }
    else
    {
      $this->addWidget(new ullWidgetDateTimeRead($this->columnConfig->getWidgetOptions(), $this->columnConfig->getWidgetAttributes()));
    }
    $this->addValidator(new sfValidatorPass()); 
  }
  
  public function getSearchType()
  {
    return 'rangeDateTime';
  }
}