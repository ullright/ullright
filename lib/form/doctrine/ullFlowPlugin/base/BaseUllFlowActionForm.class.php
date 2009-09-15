<?php

/**
 * UllFlowAction form base class.
 *
 * @package    form
 * @subpackage ull_flow_action
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseUllFlowActionForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                   => new sfWidgetFormInputHidden(),
      'namespace'            => new sfWidgetFormInput(),
      'is_status_only'       => new sfWidgetFormInputCheckbox(),
      'is_enable_validation' => new sfWidgetFormInputCheckbox(),
      'is_notify_creator'    => new sfWidgetFormInputCheckbox(),
      'is_notify_next'       => new sfWidgetFormInputCheckbox(),
      'is_in_resultlist'     => new sfWidgetFormInputCheckbox(),
      'is_show_assigned_to'  => new sfWidgetFormInputCheckbox(),
      'is_comment_mandatory' => new sfWidgetFormInputCheckbox(),
      'created_at'           => new sfWidgetFormDateTime(),
      'updated_at'           => new sfWidgetFormDateTime(),
      'creator_user_id'      => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
      'updator_user_id'      => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
      'slug'                 => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'                   => new sfValidatorDoctrineChoice(array('model' => 'UllFlowAction', 'column' => 'id', 'required' => false)),
      'namespace'            => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'is_status_only'       => new sfValidatorBoolean(array('required' => false)),
      'is_enable_validation' => new sfValidatorBoolean(array('required' => false)),
      'is_notify_creator'    => new sfValidatorBoolean(array('required' => false)),
      'is_notify_next'       => new sfValidatorBoolean(array('required' => false)),
      'is_in_resultlist'     => new sfValidatorBoolean(array('required' => false)),
      'is_show_assigned_to'  => new sfValidatorBoolean(array('required' => false)),
      'is_comment_mandatory' => new sfValidatorBoolean(array('required' => false)),
      'created_at'           => new sfValidatorDateTime(array('required' => false)),
      'updated_at'           => new sfValidatorDateTime(array('required' => false)),
      'creator_user_id'      => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
      'updator_user_id'      => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
      'slug'                 => new sfValidatorString(array('max_length' => 255, 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'UllFlowAction', 'column' => array('slug')))
    );

    $this->widgetSchema->setNameFormat('ull_flow_action[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UllFlowAction';
  }

}
