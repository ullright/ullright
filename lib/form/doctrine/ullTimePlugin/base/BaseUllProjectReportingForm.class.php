<?php

/**
 * UllProjectReporting form base class.
 *
 * @package    form
 * @subpackage ull_project_reporting
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseUllProjectReportingForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'               => new sfWidgetFormInputHidden(),
      'namespace'        => new sfWidgetFormInput(),
      'ull_user_id'      => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => false)),
      'date'             => new sfWidgetFormDate(),
      'ull_project_id'   => new sfWidgetFormDoctrineChoice(array('model' => 'UllProject', 'add_empty' => false)),
      'duration_seconds' => new sfWidgetFormInput(),
      'comment'          => new sfWidgetFormTextarea(),
      'week'             => new sfWidgetFormInput(),
      'created_at'       => new sfWidgetFormDateTime(),
      'updated_at'       => new sfWidgetFormDateTime(),
      'creator_user_id'  => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
      'updator_user_id'  => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'id'               => new sfValidatorDoctrineChoice(array('model' => 'UllProjectReporting', 'column' => 'id', 'required' => false)),
      'namespace'        => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'ull_user_id'      => new sfValidatorDoctrineChoice(array('model' => 'UllUser')),
      'date'             => new sfValidatorDate(),
      'ull_project_id'   => new sfValidatorDoctrineChoice(array('model' => 'UllProject')),
      'duration_seconds' => new sfValidatorInteger(),
      'comment'          => new sfValidatorString(array('max_length' => 4000, 'required' => false)),
      'week'             => new sfValidatorInteger(array('required' => false)),
      'created_at'       => new sfValidatorDateTime(array('required' => false)),
      'updated_at'       => new sfValidatorDateTime(array('required' => false)),
      'creator_user_id'  => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
      'updator_user_id'  => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('ull_project_reporting[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UllProjectReporting';
  }

}
