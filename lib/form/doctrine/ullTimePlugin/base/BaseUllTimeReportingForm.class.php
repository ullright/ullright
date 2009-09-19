<?php

/**
 * UllTimeReporting form base class.
 *
 * @package    form
 * @subpackage ull_time_reporting
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseUllTimeReportingForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                  => new sfWidgetFormInputHidden(),
      'namespace'           => new sfWidgetFormInput(),
      'ull_user_id'         => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => false)),
      'date'                => new sfWidgetFormDate(),
      'begin_work_at'       => new sfWidgetFormTime(),
      'end_work_at'         => new sfWidgetFormTime(),
      'begin_break1_at'     => new sfWidgetFormTime(),
      'end_break1_at'       => new sfWidgetFormTime(),
      'begin_break2_at'     => new sfWidgetFormTime(),
      'end_break2_at'       => new sfWidgetFormTime(),
      'begin_break3_at'     => new sfWidgetFormTime(),
      'end_break3_at'       => new sfWidgetFormTime(),
      'total_work_seconds'  => new sfWidgetFormInput(),
      'total_break_seconds' => new sfWidgetFormInput(),
      'created_at'          => new sfWidgetFormDateTime(),
      'updated_at'          => new sfWidgetFormDateTime(),
      'creator_user_id'     => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
      'updator_user_id'     => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'id'                  => new sfValidatorDoctrineChoice(array('model' => 'UllTimeReporting', 'column' => 'id', 'required' => false)),
      'namespace'           => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'ull_user_id'         => new sfValidatorDoctrineChoice(array('model' => 'UllUser')),
      'date'                => new sfValidatorDate(),
      'begin_work_at'       => new sfValidatorTime(array('required' => false)),
      'end_work_at'         => new sfValidatorTime(array('required' => false)),
      'begin_break1_at'     => new sfValidatorTime(array('required' => false)),
      'end_break1_at'       => new sfValidatorTime(array('required' => false)),
      'begin_break2_at'     => new sfValidatorTime(array('required' => false)),
      'end_break2_at'       => new sfValidatorTime(array('required' => false)),
      'begin_break3_at'     => new sfValidatorTime(array('required' => false)),
      'end_break3_at'       => new sfValidatorTime(array('required' => false)),
      'total_work_seconds'  => new sfValidatorInteger(array('required' => false)),
      'total_break_seconds' => new sfValidatorInteger(array('required' => false)),
      'created_at'          => new sfValidatorDateTime(array('required' => false)),
      'updated_at'          => new sfValidatorDateTime(array('required' => false)),
      'creator_user_id'     => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
      'updator_user_id'     => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('ull_time_reporting[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UllTimeReporting';
  }

}
