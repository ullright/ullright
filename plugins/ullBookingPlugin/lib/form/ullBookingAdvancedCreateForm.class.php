<?php
class UllBookingAdvancedCreateForm extends UllBookingCreateForm
{
  public function configure()
  {
    parent::configure();

    $recurringChoices = array(
      'n' => __('Not recurring', null, 'ullBookingMessages'),
      'd' => __('Daily', null, 'ullBookingMessages'),
      'w' => __('Weekly', null, 'ullBookingMessages'),
    );

    $this->setWidget('recurring', new sfWidgetFormChoice(array('choices' => $recurringChoices)));
    $this->setWidget('repeats', new ullWidgetFormInput());

    $this->getWidgetSchema()->setLabels(
    array(
        'recurring'         => __('Recurrence period', null, 'ullBookingMessages'),
        'repeats'           => __('Repeats', null, 'ullBookingMessages'),
    ));

    $this->setValidator('recurring', new sfValidatorChoice(array('choices' => array_keys($recurringChoices))));
    $this->setValidator('repeats', new sfValidatorPass());

    $this->mergePostValidator(
      new sfValidatorCallback(array('callback' => array($this, 'validateRepeats')))
    );
  
    //parent class sets this to true
    $this->validatorSchema->setOption('allow_extra_fields', false);
  }

  public function validateRepeats($validator, $values)
  {
    if ($values['recurring'] != 'n')
    {
      $repeatValidator = new sfValidatorInteger(array('min' => 2, 'max' => 52));
      try
      {
        $repeatValidator->clean($values['repeats']);
      }
      catch (sfValidatorError $error)
      {
        throw new sfValidatorErrorSchema($repeatValidator, array('repeats' => $error));
      }
    }

    return $values;
  }
}