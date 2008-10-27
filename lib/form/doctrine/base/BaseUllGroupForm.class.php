<?php

/**
 * UllGroup form base class.
 *
 * @package    form
 * @subpackage ull_group
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseUllGroupForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                    => new sfWidgetFormInputHidden(),
      'namespace'             => new sfWidgetFormInput(),
      'creator_user_id'       => new sfWidgetFormDoctrineSelect(array('model' => 'UllUser', 'add_empty' => true)),
      'updator_user_id'       => new sfWidgetFormDoctrineSelect(array('model' => 'UllUser', 'add_empty' => true)),
      'first_name'            => new sfWidgetFormInput(),
      'last_name'             => new sfWidgetFormInput(),
      'name'                  => new sfWidgetFormInput(),
      'email'                 => new sfWidgetFormInput(),
      'password'              => new sfWidgetFormInput(),
      'user_type'             => new sfWidgetFormInput(),
      'type'                  => new sfWidgetFormInput(),
      'created_at'            => new sfWidgetFormDateTime(),
      'updated_at'            => new sfWidgetFormDateTime(),
      'ull_user_list' => new sfWidgetFormDoctrineSelectMany(array('model' => 'UllUser')),
    ));

    $this->setValidators(array(
      'id'                    => new sfValidatorDoctrineChoice(array('model' => 'UllGroup', 'column' => 'id', 'required' => false)),
      'namespace'             => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'creator_user_id'       => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
      'updator_user_id'       => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
      'first_name'            => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'last_name'             => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'name'                  => new sfValidatorString(array('max_length' => 64)),
      'email'                 => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'password'              => new sfValidatorString(array('max_length' => 40, 'required' => false)),
      'user_type'             => new sfValidatorInteger(array('required' => false)),
      'type'                  => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'created_at'            => new sfValidatorDateTime(array('required' => false)),
      'updated_at'            => new sfValidatorDateTime(array('required' => false)),
      'ull_user_list' => new sfValidatorDoctrineChoiceMany(array('model' => 'UllUser', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('ull_group[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UllGroup';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['ull_user_list']))
    {
      $values = array();
      foreach ($this->object->UllUser as $obj)
      {
        $values[] = current($obj->identifier());
      }
      $this->object->clearRelated('UllUser');
      $this->setDefault('ull_user_list', $values);
    }

  }

  protected function doSave($con = null)
  {
    parent::doSave($con);

    $this->saveUllUserList($con);
  }

  public function saveUllUserList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['ull_user_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (is_null($con))
    {
      $con = $this->getConnection();
    }

    $q = Doctrine_Query::create()
          ->delete()
          ->from('UllEntityGroup r')
          ->where('r.group_id = ?', current($this->object->identifier()))
          ->execute();

    $values = $this->getValue('ull_user_list');
    if (is_array($values))
    {
      foreach ($values as $value)
      {
        $obj = new UllEntityGroup();
        $obj->group_id = current($this->object->identifier());
        $obj->entity_id = $value;
        $obj->save();
      }
    }
  }

}