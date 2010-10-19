<?php

/**
 * Specific generator for project effort creation and editing.
 * Supports creation of recurring project efforts.
 * 
 * See also: ullTimeProjectEffortForm
 */
class ullTimeProjectEffortGenerator extends ullTableToolGenerator
{
  //date of a project effort, needed for recurring functionality
  protected $effortDate;

  /**
   * Constructs a new ullTimeProjectEffortGenerator instance and
   * sets internal form class name.
   * 
   * @param $lockingStatus effort lock status
   */
  public function __construct($lockingStatus, $effortDate)
  {
    $this->effortDate = $effortDate;
    
    parent::__construct('UllProjectReporting', $lockingStatus);
  }
  
  /**
   * Adds a 'recurring until' date columns in case of
   * effort creation.
   */
  protected function customizeColumnsConfig()
  {
    if ($this->isAction('createProject')
      && UllUserTable::hasPermission('ull_time_enter_future_periods'))
    {
      $this->getColumnsConfig()->create('recurring_until')
        ->setLabel(__('Recurring until', null, 'common'))
        ->setMetaWidgetClassName('ullMetaWidgetDate')
        ->setOption('use_inclusive_error_messages', true)
        ->setWidgetOption('min_date', ull_format_date(strtotime('+1 day', strtotime($this->effortDate))))
        ->setWidgetOption('max_date', ull_format_date(strtotime('+6 weeks', strtotime($this->effortDate))))
        ->setValidatorOption('min', strtotime('+1 day', strtotime($this->effortDate)))
        ->setValidatorOption('max', strtotime('+6 weeks', strtotime($this->effortDate)))
        ->setHelp(__('Repeat the new effort daily until this date (working days only)', null, 'ullTimeMessages'))
        ->setWidgetAttribute('class', 'advanced_form_field')
        ->setAutoRender(false)
      ;
      
      $this->getColumnsConfig()->orderBottom(array('comment', 'recurring_until'));
    }
    
    if ($this->isAction('editProject'))
    {
      $this->getColumnsConfig()->offsetGet('date')
        ->setAccess('w')
        ->setWidgetAttribute('class', 'advanced_form_field')
      ;
      
      if (!UllUserTable::hasPermission('ull_time_enter_future_periods'))
      {
        $this->getColumnsConfig()->offsetGet('date')
          ->setOption('use_inclusive_error_messages', true)
          ->setWidgetOption('max_date', ull_format_date(strtotime('today')))
          ->setValidatorOption('max', strtotime('today'))
          ->setHelp(__('If you need to enter a future date, please contact your superior', null, 'ullTimeMessages'))
        ;
      }
      
      $this->getColumnsConfig()->orderBottom(array('date'));
    } 
  }
}
