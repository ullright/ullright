<?php

/**
 * UllFlowColumnConfig form base class.
 *
 * @package    form
 * @subpackage ull_flow_column_config
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseUllFlowColumnConfigForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                 => new sfWidgetFormInputHidden(),
      'namespace'          => new sfWidgetFormInput(),
      'ull_flow_app_id'    => new sfWidgetFormDoctrineChoice(array('model' => 'UllFlowApp', 'add_empty' => false)),
      'sequence'           => new sfWidgetFormInput(),
      'ull_column_type_id' => new sfWidgetFormDoctrineChoice(array('model' => 'UllColumnType', 'add_empty' => true)),
      'options'            => new sfWidgetFormTextarea(),
      'is_enabled'         => new sfWidgetFormInputCheckbox(),
      'is_mandatory'       => new sfWidgetFormInputCheckbox(),
      'is_subject'         => new sfWidgetFormInputCheckbox(),
      'is_priority'        => new sfWidgetFormInputCheckbox(),
      'is_tagging'         => new sfWidgetFormInputCheckbox(),
      'default_value'      => new sfWidgetFormInput(),
      'created_at'         => new sfWidgetFormDateTime(),
      'updated_at'         => new sfWidgetFormDateTime(),
      'creator_user_id'    => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
      'updator_user_id'    => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
      'slug'               => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'                 => new sfValidatorDoctrineChoice(array('model' => 'UllFlowColumnConfig', 'column' => 'id', 'required' => false)),
      'namespace'          => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'ull_flow_app_id'    => new sfValidatorDoctrineChoice(array('model' => 'UllFlowApp')),
      'sequence'           => new sfValidatorInteger(array('required' => false)),
      'ull_column_type_id' => new sfValidatorDoctrineChoice(array('model' => 'UllColumnType', 'required' => false)),
      'options'            => new sfValidatorString(array('required' => false)),
      'is_enabled'         => new sfValidatorBoolean(array('required' => false)),
      'is_mandatory'       => new sfValidatorBoolean(array('required' => false)),
      'is_subject'         => new sfValidatorBoolean(array('required' => false)),
      'is_priority'        => new sfValidatorBoolean(array('required' => false)),
      'is_tagging'         => new sfValidatorBoolean(array('required' => false)),
      'default_value'      => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'created_at'         => new sfValidatorDateTime(array('required' => false)),
      'updated_at'         => new sfValidatorDateTime(array('required' => false)),
      'creator_user_id'    => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
      'updator_user_id'    => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
      'slug'               => new sfValidatorString(array('max_length' => 255, 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'UllFlowColumnConfig', 'column' => array('slug')))
    );

    $this->widgetSchema->setNameFormat('ull_flow_column_config[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UllFlowColumnConfig';
  }

}
