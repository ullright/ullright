<?php

/**
 * UllFlowMemory form base class.
 *
 * @package    form
 * @subpackage ull_flow_memory
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseUllFlowMemoryForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                        => new sfWidgetFormInputHidden(),
      'namespace'                 => new sfWidgetFormInput(),
      'ull_flow_doc_id'           => new sfWidgetFormDoctrineChoice(array('model' => 'UllFlowDoc', 'add_empty' => false)),
      'ull_flow_step_id'          => new sfWidgetFormDoctrineChoice(array('model' => 'UllFlowStep', 'add_empty' => false)),
      'ull_flow_action_id'        => new sfWidgetFormDoctrineChoice(array('model' => 'UllFlowAction', 'add_empty' => false)),
      'assigned_to_ull_entity_id' => new sfWidgetFormDoctrineChoice(array('model' => 'UllEntity', 'add_empty' => false)),
      'comment'                   => new sfWidgetFormInput(),
      'creator_ull_entity_id'     => new sfWidgetFormDoctrineChoice(array('model' => 'UllEntity', 'add_empty' => false)),
      'created_at'                => new sfWidgetFormDateTime(),
      'updated_at'                => new sfWidgetFormDateTime(),
      'creator_user_id'           => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
      'updator_user_id'           => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'id'                        => new sfValidatorDoctrineChoice(array('model' => 'UllFlowMemory', 'column' => 'id', 'required' => false)),
      'namespace'                 => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'ull_flow_doc_id'           => new sfValidatorDoctrineChoice(array('model' => 'UllFlowDoc')),
      'ull_flow_step_id'          => new sfValidatorDoctrineChoice(array('model' => 'UllFlowStep')),
      'ull_flow_action_id'        => new sfValidatorDoctrineChoice(array('model' => 'UllFlowAction')),
      'assigned_to_ull_entity_id' => new sfValidatorDoctrineChoice(array('model' => 'UllEntity')),
      'comment'                   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'creator_ull_entity_id'     => new sfValidatorDoctrineChoice(array('model' => 'UllEntity')),
      'created_at'                => new sfValidatorDateTime(array('required' => false)),
      'updated_at'                => new sfValidatorDateTime(array('required' => false)),
      'creator_user_id'           => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
      'updator_user_id'           => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('ull_flow_memory[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UllFlowMemory';
  }

}