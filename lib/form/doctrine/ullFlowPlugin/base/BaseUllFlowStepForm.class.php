<?php

/**
 * UllFlowStep form base class.
 *
 * @package    form
 * @subpackage ull_flow_step
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseUllFlowStepForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'namespace'       => new sfWidgetFormInput(),
      'ull_flow_app_id' => new sfWidgetFormDoctrineChoice(array('model' => 'UllFlowApp', 'add_empty' => false)),
      'is_start'        => new sfWidgetFormInputCheckbox(),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
      'creator_user_id' => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
      'updator_user_id' => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
      'slug'            => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorDoctrineChoice(array('model' => 'UllFlowStep', 'column' => 'id', 'required' => false)),
      'namespace'       => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'ull_flow_app_id' => new sfValidatorDoctrineChoice(array('model' => 'UllFlowApp')),
      'is_start'        => new sfValidatorBoolean(array('required' => false)),
      'created_at'      => new sfValidatorDateTime(array('required' => false)),
      'updated_at'      => new sfValidatorDateTime(array('required' => false)),
      'creator_user_id' => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
      'updator_user_id' => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
      'slug'            => new sfValidatorString(array('max_length' => 255, 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'UllFlowStep', 'column' => array('slug')))
    );

    $this->widgetSchema->setNameFormat('ull_flow_step[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UllFlowStep';
  }

}
