<?php

/**
 * UllFlowValue form base class.
 *
 * @package    form
 * @subpackage ull_flow_value
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseUllFlowValueForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                        => new sfWidgetFormInputHidden(),
      'namespace'                 => new sfWidgetFormInput(),
      'ull_flow_doc_id'           => new sfWidgetFormDoctrineChoice(array('model' => 'UllFlowDoc', 'add_empty' => false)),
      'ull_flow_column_config_id' => new sfWidgetFormDoctrineChoice(array('model' => 'UllFlowColumnConfig', 'add_empty' => false)),
      'ull_flow_memory_id'        => new sfWidgetFormInput(),
      'value'                     => new sfWidgetFormTextarea(),
      'created_at'                => new sfWidgetFormDateTime(),
      'updated_at'                => new sfWidgetFormDateTime(),
      'creator_user_id'           => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
      'updator_user_id'           => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'id'                        => new sfValidatorDoctrineChoice(array('model' => 'UllFlowValue', 'column' => 'id', 'required' => false)),
      'namespace'                 => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'ull_flow_doc_id'           => new sfValidatorDoctrineChoice(array('model' => 'UllFlowDoc')),
      'ull_flow_column_config_id' => new sfValidatorDoctrineChoice(array('model' => 'UllFlowColumnConfig')),
      'ull_flow_memory_id'        => new sfValidatorInteger(array('required' => false)),
      'value'                     => new sfValidatorString(array('max_length' => 65536, 'required' => false)),
      'created_at'                => new sfValidatorDateTime(array('required' => false)),
      'updated_at'                => new sfValidatorDateTime(array('required' => false)),
      'creator_user_id'           => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
      'updator_user_id'           => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('ull_flow_value[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UllFlowValue';
  }

}