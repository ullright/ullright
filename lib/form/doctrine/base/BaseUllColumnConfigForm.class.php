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
      'creator_user_id'    => new sfWidgetFormDoctrineSelect(array('model' => 'UllUser', 'add_empty' => true)),
      'updator_user_id'    => new sfWidgetFormDoctrineSelect(array('model' => 'UllUser', 'add_empty' => true)),
      'db_table_name'      => new sfWidgetFormInput(),
      'db_column_name'     => new sfWidgetFormInput(),
      'label'              => new sfWidgetFormInput(),
      'description'        => new sfWidgetFormTextarea(),
      'ull_column_type_id' => new sfWidgetFormDoctrineSelect(array('model' => 'UllColumnType', 'add_empty' => true)),
      'options'            => new sfWidgetFormTextarea(),
      'enabled'            => new sfWidgetFormInputCheckbox(),
      'show_in_list'       => new sfWidgetFormInputCheckbox(),
      'created_at'         => new sfWidgetFormDateTime(),
      'updated_at'         => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                 => new sfValidatorDoctrineChoice(array('model' => 'UllColumnConfig', 'column' => 'id', 'required' => false)),
      'namespace'          => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'creator_user_id'    => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
      'updator_user_id'    => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
      'db_table_name'      => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'db_column_name'     => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'label'              => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'description'        => new sfValidatorString(array('required' => false)),
      'ull_column_type_id' => new sfValidatorDoctrineChoice(array('model' => 'UllColumnType', 'required' => false)),
      'options'            => new sfValidatorString(array('required' => false)),
      'enabled'            => new sfValidatorBoolean(array('required' => false)),
      'show_in_list'       => new sfValidatorBoolean(array('required' => false)),
      'created_at'         => new sfValidatorDateTime(array('required' => false)),
      'updated_at'         => new sfValidatorDateTime(array('required' => false)),
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