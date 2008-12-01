<?php

/**
 * UllEntityGroup form base class.
 *
 * @package    form
 * @subpackage ull_entity_group
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseUllEntityGroupForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'namespace'       => new sfWidgetFormInput(),
      'creator_user_id' => new sfWidgetFormDoctrineSelect(array('model' => 'UllUser', 'add_empty' => true)),
      'updator_user_id' => new sfWidgetFormDoctrineSelect(array('model' => 'UllUser', 'add_empty' => true)),
      'ull_entity_id'   => new sfWidgetFormInputHidden(),
      'ull_group_id'    => new sfWidgetFormInputHidden(),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'namespace'       => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'creator_user_id' => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
      'updator_user_id' => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
      'ull_entity_id'   => new sfValidatorDoctrineChoice(array('model' => 'UllEntityGroup', 'column' => 'ull_entity_id', 'required' => false)),
      'ull_group_id'    => new sfValidatorDoctrineChoice(array('model' => 'UllEntityGroup', 'column' => 'ull_group_id', 'required' => false)),
      'created_at'      => new sfValidatorDateTime(array('required' => false)),
      'updated_at'      => new sfValidatorDateTime(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('ull_entity_group[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UllEntityGroup';
  }

}