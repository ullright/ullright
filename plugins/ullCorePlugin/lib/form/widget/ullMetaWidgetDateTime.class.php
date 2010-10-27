<?php

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
        $dateReminder => 'ull_flow_reminder_date_color',
        $dateOverdue => 'ull_flow_overdue_date_color',
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