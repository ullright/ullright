<?php

/**
 * UllGroupPermission form base class.
 *
 * @package    form
 * @subpackage ull_group_permission
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseUllGroupPermissionForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                => new sfWidgetFormInputHidden(),
      'namespace'         => new sfWidgetFormInput(),
      'ull_group_id'      => new sfWidgetFormDoctrineSelect(array('model' => 'UllGroup', 'add_empty' => false)),
      'ull_permission_id' => new sfWidgetFormDoctrineSelect(array('model' => 'UllPermission', 'add_empty' => false)),
      'created_at'        => new sfWidgetFormDateTime(),
      'updated_at'        => new sfWidgetFormDateTime(),
      'creator_user_id'   => new sfWidgetFormDoctrineSelect(array('model' => 'UllUser', 'add_empty' => true)),
      'updator_user_id'   => new sfWidgetFormDoctrineSelect(array('model' => 'UllUser', 'add_empty' => true)),
      'version'           => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'                => new sfValidatorDoctrineChoice(array('model' => 'UllGroupPermission', 'column' => 'id', 'required' => false)),
      'namespace'         => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'ull_group_id'      => new sfValidatorDoctrineChoice(array('model' => 'UllGroup')),
      'ull_permission_id' => new sfValidatorDoctrineChoice(array('model' => 'UllPermission')),
      'created_at'        => new sfValidatorDateTime(array('required' => false)),
      'updated_at'        => new sfValidatorDateTime(array('required' => false)),
      'creator_user_id'   => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
      'updator_user_id'   => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
      'version'           => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('ull_group_permission[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UllGroupPermission';
  }

}