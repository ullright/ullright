<?php

/**
 * UllFlowDoc form base class.
 *
 * @package    form
 * @subpackage ull_flow_doc
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseUllFlowDocForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                           => new sfWidgetFormInputHidden(),
      'namespace'                    => new sfWidgetFormInput(),
      'creator_user_id'              => new sfWidgetFormDoctrineSelect(array('model' => 'UllUser', 'add_empty' => true)),
      'updator_user_id'              => new sfWidgetFormDoctrineSelect(array('model' => 'UllUser', 'add_empty' => true)),
      'ull_flow_app_id'              => new sfWidgetFormDoctrineSelect(array('model' => 'UllFlowApp', 'add_empty' => false)),
      'title'                        => new sfWidgetFormInput(),
      'ull_flow_action_id'           => new sfWidgetFormDoctrineSelect(array('model' => 'UllFlowAction', 'add_empty' => true)),
      'assigned_to_ull_entity_id'    => new sfWidgetFormDoctrineSelect(array('model' => 'UllEntity', 'add_empty' => false)),
      'assigned_to_ull_flow_step_id' => new sfWidgetFormDoctrineSelect(array('model' => 'UllFlowStep', 'add_empty' => false)),
      'dirty'                        => new sfWidgetFormInput(),
      'created_at'                   => new sfWidgetFormDateTime(),
      'updated_at'                   => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                           => new sfValidatorDoctrineChoice(array('model' => 'UllFlowDoc', 'column' => 'id', 'required' => false)),
      'namespace'                    => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'creator_user_id'              => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
      'updator_user_id'              => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
      'ull_flow_app_id'              => new sfValidatorDoctrineChoice(array('model' => 'UllFlowApp')),
      'title'                        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'ull_flow_action_id'           => new sfValidatorDoctrineChoice(array('model' => 'UllFlowAction', 'required' => false)),
      'assigned_to_ull_entity_id'    => new sfValidatorDoctrineChoice(array('model' => 'UllEntity')),
      'assigned_to_ull_flow_step_id' => new sfValidatorDoctrineChoice(array('model' => 'UllFlowStep')),
      'dirty'                        => new sfValidatorInteger(array('required' => false)),
      'created_at'                   => new sfValidatorDateTime(array('required' => false)),
      'updated_at'                   => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('ull_flow_doc[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UllFlowDoc';
  }

}