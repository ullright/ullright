<?php

/**
 * TestTable form base class.
 *
 * @package    form
 * @subpackage test_table
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseTestTableForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                => new sfWidgetFormInputHidden(),
      'namespace'         => new sfWidgetFormInput(),
      'creator_user_id'   => new sfWidgetFormDoctrineSelect(array('model' => 'UllUser', 'add_empty' => true)),
      'updator_user_id'   => new sfWidgetFormDoctrineSelect(array('model' => 'UllUser', 'add_empty' => true)),
      'my_string'         => new sfWidgetFormInput(),
      'my_text'           => new sfWidgetFormTextarea(),
      'my_boolean'        => new sfWidgetFormInputCheckbox(),
      'my_email'          => new sfWidgetFormInput(),
      'my_useless_column' => new sfWidgetFormInput(),
      'ull_user_id'       => new sfWidgetFormDoctrineSelect(array('model' => 'UllUser', 'add_empty' => true)),
      'created_at'        => new sfWidgetFormDateTime(),
      'updated_at'        => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                => new sfValidatorDoctrineChoice(array('model' => 'TestTable', 'column' => 'id', 'required' => false)),
      'namespace'         => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'creator_user_id'   => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
      'updator_user_id'   => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
      'my_string'         => new sfValidatorString(array('max_length' => 64)),
      'my_text'           => new sfValidatorString(array('required' => false)),
      'my_boolean'        => new sfValidatorBoolean(array('required' => false)),
      'my_email'          => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'my_useless_column' => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'ull_user_id'       => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
      'created_at'        => new sfValidatorDateTime(array('required' => false)),
      'updated_at'        => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('test_table[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'TestTable';
  }

}