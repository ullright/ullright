<?php
class UllBookingCreateForm extends sfForm
{
  public function configure()
  {
    $minDuration = 15 * 60; //15 minutes is the shortest duration we allow
    
    //note: 'duration' does not use the ullWidgetTimeDurationWrite on purpose,
    //since it does not remember values after submission of an invalid form.
    
    $this->setWidgets(array(
      //simple stuff
      'date' => new ullWidgetDateWrite(),
      'name' => new ullWidgetFormInput(),
      'time' => new ullWidgetTimeWrite(array('startHour' => 9, 'endHour' => 21)),
      'duration' => new ullWidgetTimeWrite(array('startHour' => 0, 'endHour' => 12)),
      'booking_resource' => new sfWidgetFormDoctrineChoice(array(
                                    'model' => 'UllBookingResource',
                                    'table_method' => 'findBookableResources',
                                    'add_empty' => true)),
    ));
    
    $this->getWidgetSchema()->setLabels(
      array(
        'date'              => __('Date', null, 'common'),
        'name'              => __('Name', null, 'common'),
        'time'              => __('Start', null, 'common'),
        'duration'          => __('Duration', null, 'common'),
        'booking_resource'  => __('Resource', null, 'ullBookingMessages'),
      ));

    $this->setValidators(array(
      'date' => new sfValidatorDate(),
      'name' => new ullValidatorPurifiedString(),
      'time' => new ullValidatorTimeDuration(),
      'duration' => new ullValidatorTimeDuration(array('min' => $minDuration), array('min' => 'Minimum duration is 15 minutes.')),
      'booking_resource' => new sfValidatorDoctrineChoice(array('model' => 'UllBookingResource')),
    ));
   
    $this->getWidgetSchema()->setNameFormat('fields[%s]');
    $this->validatorSchema->setOption('allow_extra_fields', true);
  }
}