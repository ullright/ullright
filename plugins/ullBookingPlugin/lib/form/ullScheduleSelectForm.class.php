<?php
class UllScheduleSelectForm extends sfForm
{
  public function configure()
  {    
    $this->setWidgets(array(
      'date' => new ullWidgetDateWrite(),
    ));
    
    $this->getWidgetSchema()->setLabels(
      array(
        'date' => __('Date', null, 'common'),
      ));

    $this->setValidators(array(
      'date' => new ullValidatorDate(),
    ));
   
    $this->getWidgetSchema()->setNameFormat('fields[%s]');
  }
}