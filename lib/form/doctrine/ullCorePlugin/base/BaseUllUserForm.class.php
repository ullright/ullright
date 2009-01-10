<?php

/**
 * UllUser form base class.
 *
 * @package    form
 * @subpackage ull_user
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseUllUserForm extends BaseFormDoctrine
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
      'type'            => new sfWidgetFormInput(),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
      'creator_user_id' => new sfWidgetFormDoctrineSelect(array('model' => 'UllUser', 'add_empty' => true)),
      'updator_user_id' => new sfWidgetFormDoctrineSelect(array('model' => 'UllUser', 'add_empty' => true)),
      'ull_group_list'  => new sfWidgetFormDoctrineChoiceMany(array('model' => 'UllGroup')),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'column' => 'id', 'required' => false)),
      'namespace'       => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'first_name'      => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'last_name'       => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'display_name'    => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'username'        => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'email'           => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'password'        => new sfValidatorString(array('max_length' => 40, 'required' => false)),
      'type'            => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'created_at'      => new sfValidatorDateTime(array('required' => false)),
      'updated_at'      => new sfValidatorDateTime(array('required' => false)),
      'creator_user_id' => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
      'updator_user_id' => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
      'ull_group_list'  => new sfValidatorDoctrineChoiceMany(array('model' => 'UllGroup', 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'UllUser', 'column' => array('username')))
    );

    $this->widgetSchema->setNameFormat('ull_user[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UllUser';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['ull_group_list']))
    {
      $this->setDefault('ull_group_list', $this->object->UllGroup->getPrimaryKeys());
    }

  }

  protected function doSave($con = null)
  {
    parent::doSave($con);

    $this->saveUllGroupList($con);
  }

  public function saveUllGroupList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['ull_group_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (is_null($con))
    {
      $con = $this->getConnection();
    }

    $this->object->unlink('UllGroup', array());

    $values = $this->getValue('ull_group_list');
    if (is_array($values))
    {
      $this->object->link('UllGroup', $values);
    }
  }

}