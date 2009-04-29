<?php

/**
 * UllColumnConfig form base class.
 *
 * @package    form
 * @subpackage ull_column_config
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseUllColumnConfigForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                 => new sfWidgetFormInputHidden(),
      'namespace'          => new sfWidgetFormInput(),
      'db_table_name'      => new sfWidgetFormInput(),
      'db_column_name'     => new sfWidgetFormInput(),
      'ull_column_type_id' => new sfWidgetFormDoctrineChoice(array('model' => 'UllColumnType', 'add_empty' => true)),
      'options'            => new sfWidgetFormTextarea(),
      'is_enabled'         => new sfWidgetFormInputCheckbox(),
      'is_in_list'         => new sfWidgetFormInputCheckbox(),
      'created_at'         => new sfWidgetFormDateTime(),
      'updated_at'         => new sfWidgetFormDateTime(),
      'creator_user_id'    => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
      'updator_user_id'    => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'id'                 => new sfValidatorDoctrineChoice(array('model' => 'UllColumnConfig', 'column' => 'id', 'required' => false)),
      'namespace'          => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'db_table_name'      => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'db_column_name'     => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'ull_column_type_id' => new sfValidatorDoctrineChoice(array('model' => 'UllColumnType', 'required' => false)),
      'options'            => new sfValidatorString(array('required' => false)),
      'is_enabled'         => new sfValidatorBoolean(array('required' => false)),
      'is_in_list'         => new sfValidatorBoolean(array('required' => false)),
      'created_at'         => new sfValidatorDateTime(array('required' => false)),
      'updated_at'         => new sfValidatorDateTime(array('required' => false)),
      'creator_user_id'    => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
      'updator_user_id'    => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('ull_column_config[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UllColumnConfig';
  }

}