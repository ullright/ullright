<?php

/**
 * UllFlowAppPermission form base class.
 *
 * @package    form
 * @subpackage ull_flow_app_permission
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseUllFlowAppPermissionForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                => new sfWidgetFormInputHidden(),
      'namespace'         => new sfWidgetFormInput(),
      'creator_user_id'   => new sfWidgetFormDoctrineSelect(array('model' => 'UllUser', 'add_empty' => true)),
      'updator_user_id'   => new sfWidgetFormDoctrineSelect(array('model' => 'UllUser', 'add_empty' => true)),
      'ull_flow_app_id'   => new sfWidgetFormDoctrineSelect(array('model' => 'UllFlowApp', 'add_empty' => false)),
      'ull_permission_id' => new sfWidgetFormDoctrineSelect(array('model' => 'UllPermission', 'add_empty' => false)),
      'created_at'        => new sfWidgetFormDateTime(),
      'updated_at'        => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                => new sfValidatorDoctrineChoice(array('model' => 'UllFlowAppPermission', 'column' => 'id', 'required' => false)),
      'namespace'         => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'creator_user_id'   => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
      'updator_user_id'   => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
      'ull_flow_app_id'   => new sfValidatorDoctrineChoice(array('model' => 'UllFlowApp')),
      'ull_permission_id' => new sfValidatorDoctrineChoice(array('model' => 'UllPermission')),
      'created_at'        => new sfValidatorDateTime(array('required' => false)),
      'updated_at'        => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('ull_flow_app_permission[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UllFlowAppPermission';
  }

}