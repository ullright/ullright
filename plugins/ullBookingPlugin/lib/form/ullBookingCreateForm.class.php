<?php
class UllBookingCreateForm extends sfForm
{
  protected $minDuration = 900; // 15 * 60 seconds is the shortest booking duration we allow
  
  public function configure()
  {
    //note: 'duration' does not use the ullWidgetTimeDurationWrite on purpose,
    //since it does not remember values after submission of an invalid form.
    
    $this->setWidgets(array(
      //simple stuff
      'date' => new ullWidgetDateWrite(),
      'name' => new ullWidgetFormInput(),
      'time' => new ullWidgetTimeWrite(array('startHour' => 9, 'endHour' => 21)),
      'end' => new ullWidgetTimeWrite(array('startHour' => 9, 'endHour' => 22)),
      //'duration' => new ullWidgetTimeWrite(array('startHour' => 0, 'endHour' => 12)),
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
        'end'               => __('Ende', null, 'common'),
        //'duration'          => __('Duration', null, 'common'),
        'booking_resource'  => __('Resource', null, 'ullBookingMessages'),
      ));

    $this->setValidators(array(
      'date' => new sfValidatorDate(),
      'name' => new ullValidatorPurifiedString(),
      'time' => new ullValidatorTimeDuration(),
      'end' => new ullValidatorTimeDuration(),
      //'duration' => new ullValidatorTimeDuration(array('min' => $minDuration), array('min' => 'Minimum duration is 15 minutes.')),
      'booking_resource' => new sfValidatorDoctrineChoice(array('model' => 'UllBookingResource')),
    ));
   
    $this->mergePostValidator(
      new sfValidatorCallback(array('callback' => array($this, 'validateTimeRange')))
    );
    
    $this->getWidgetSchema()->setNameFormat('fields[%s]');
    $this->validatorSchema->setOption('allow_extra_fields', true);
  }
  
  public function validateTimeRange($validator, $values)
  {
    if (($values['time'] + $this->minDuration) > $values['end'])
    {
      //throw a global error, for an example on how to bind
      //to a field see ullBookingAdvancedCreateForm::validateRepeats
      throw new sfValidatorError($validator,
        __('Minimum booking duration is 15 minutes.', null, 'ullBookingMessages'));
    }
    
    return $values;
  }
}