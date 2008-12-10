<?php

/**
 * UllFlowStepAction form base class.
 *
 * @package    form
 * @subpackage ull_flow_step_action
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseUllFlowStepActionForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                 => new sfWidgetFormInputHidden(),
      'namespace'          => new sfWidgetFormInput(),
      'creator_user_id'    => new sfWidgetFormDoctrineSelect(array('model' => 'UllUser', 'add_empty' => true)),
      'updator_user_id'    => new sfWidgetFormDoctrineSelect(array('model' => 'UllUser', 'add_empty' => true)),
      'ull_flow_step_id'   => new sfWidgetFormDoctrineSelect(array('model' => 'UllFlowStep', 'add_empty' => true)),
      'ull_flow_action_id' => new sfWidgetFormDoctrineSelect(array('model' => 'UllFlowAction', 'add_empty' => true)),
      'options'            => new sfWidgetFormInput(),
      'sequence'           => new sfWidgetFormInput(),
      'created_at'         => new sfWidgetFormDateTime(),
      'updated_at'         => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                 => new sfValidatorDoctrineChoice(array('model' => 'UllFlowStepAction', 'column' => 'id', 'required' => false)),
      'namespace'          => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'creator_user_id'    => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
      'updator_user_id'    => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
      'ull_flow_step_id'   => new sfValidatorDoctrineChoice(array('model' => 'UllFlowStep', 'required' => false)),
      'ull_flow_action_id' => new sfValidatorDoctrineChoice(array('model' => 'UllFlowAction', 'required' => false)),
      'options'            => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'sequence'           => new sfValidatorInteger(array('required' => false)),
      'created_at'         => new sfValidatorDateTime(array('required' => false)),
      'updated_at'         => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('ull_flow_step_action[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UllFlowStepAction';
  }

}