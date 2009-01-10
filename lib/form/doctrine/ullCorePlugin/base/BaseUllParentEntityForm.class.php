<?php

/**
 * UllParentEntity form base class.
 *
 * @package    form
 * @subpackage ull_parent_entity
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseUllParentEntityForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'namespace'       => new sfWidgetFormInput(),
      'first_name'      => new sfWidgetFormInput(),
      'last_name'       => new sfWidgetFormInput(),
      'display_name'    => new sfWidgetFormInput(),
      'username'        => new sfWidgetFormInput(),
      'email'           => new sfWidgetFormInput(),
      'password'        => new sfWidgetFormInput(),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
      'creator_user_id' => new sfWidgetFormDoctrineSelect(array('model' => 'UllUser', 'add_empty' => true)),
      'updator_user_id' => new sfWidgetFormDoctrineSelect(array('model' => 'UllUser', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorDoctrineChoice(array('model' => 'UllParentEntity', 'column' => 'id', 'required' => false)),
      'namespace'       => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'first_name'      => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'last_name'       => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'display_name'    => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'username'        => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'email'           => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'password'        => new sfValidatorString(array('max_length' => 40, 'required' => false)),
      'created_at'      => new sfValidatorDateTime(array('required' => false)),
      'updated_at'      => new sfValidatorDateTime(array('required' => false)),
      'creator_user_id' => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
      'updator_user_id' => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'UllParentEntity', 'column' => array('username')))
    );

    $this->widgetSchema->setNameFormat('ull_parent_entity[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UllParentEntity';
  }

}