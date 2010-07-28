<?php
class UllScheduleSelectForm extends sfForm
{
  public function configure()
  {    
    $this->setWidgets(array(
      'date' => new ullWidgetDateWrite(
        array('year_range' => 'c-3:c+3', 'max_date' => '+10y', 'min_date' => '-10y')),
    ));
    
    $this->getWidgetSchema()->setLabels(
      array(
        'date' => __('Date', null, 'common'),
      ));

    $this->setValidators(array(
      'date' => new ullValidatorDate(
        array(
          'min' => strtotime('-10 years midnight'),
          'max' => strtotime('+10 years midnight'),
          'date_format_range_error' => ull_date_pattern(true, true)
        )
    )));
   
    $this->getWidgetSchema()->setNameFormat('fields[%s]');
  }
}