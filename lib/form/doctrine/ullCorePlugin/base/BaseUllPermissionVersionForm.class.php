<?php

/**
 * UllPermissionVersion form base class.
 *
 * @package    form
 * @subpackage ull_permission_version
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseUllPermissionVersionForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                    => new sfWidgetFormInputHidden(),
      'namespace'             => new sfWidgetFormInput(),
      'slug'                  => new sfWidgetFormInput(),
      'created_at'            => new sfWidgetFormDateTime(),
      'updated_at'            => new sfWidgetFormDateTime(),
      'creator_user_id'       => new sfWidgetFormInput(),
      'updator_user_id'       => new sfWidgetFormDoctrineSelect(array('model' => 'UllUser', 'add_empty' => true)),
      'version'               => new sfWidgetFormInputHidden(),
      'reference_version'     => new sfWidgetFormInput(),
      'scheduled_update_date' => new sfWidgetFormDate(),
      'done_at'               => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                    => new sfValidatorDoctrineChoice(array('model' => 'UllPermissionVersion', 'column' => 'id', 'required' => false)),
      'namespace'             => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'slug'                  => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'created_at'            => new sfValidatorDateTime(array('required' => false)),
      'updated_at'            => new sfValidatorDateTime(array('required' => false)),
      'creator_user_id'       => new sfValidatorInteger(array('required' => false)),
      'updator_user_id'       => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
      'version'               => new sfValidatorDoctrineChoice(array('model' => 'UllPermissionVersion', 'column' => 'version', 'required' => false)),
      'reference_version'     => new sfValidatorInteger(array('required' => false)),
      'scheduled_update_date' => new sfValidatorDate(array('required' => false)),
      'done_at'               => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('ull_permission_version[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UllPermissionVersion';
  }

}