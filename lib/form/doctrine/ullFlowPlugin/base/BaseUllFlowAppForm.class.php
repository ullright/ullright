<?php

/**
 * UllFlowApp form base class.
 *
 * @package    form
 * @subpackage ull_flow_app
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseUllFlowAppForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                  => new sfWidgetFormInputHidden(),
      'namespace'           => new sfWidgetFormInput(),
      'list_columns'        => new sfWidgetFormInput(),
      'is_public'           => new sfWidgetFormInputCheckbox(),
      'created_at'          => new sfWidgetFormDateTime(),
      'updated_at'          => new sfWidgetFormDateTime(),
      'creator_user_id'     => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
      'updator_user_id'     => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
      'slug'                => new sfWidgetFormInput(),
      'ull_permission_list' => new sfWidgetFormDoctrineChoiceMany(array('model' => 'UllPermission')),
    ));

    $this->setValidators(array(
      'id'                  => new sfValidatorDoctrineChoice(array('model' => 'UllFlowApp', 'column' => 'id', 'required' => false)),
      'namespace'           => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'list_columns'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'is_public'           => new sfValidatorBoolean(array('required' => false)),
      'created_at'          => new sfValidatorDateTime(array('required' => false)),
      'updated_at'          => new sfValidatorDateTime(array('required' => false)),
      'creator_user_id'     => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
      'updator_user_id'     => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
      'slug'                => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'ull_permission_list' => new sfValidatorDoctrineChoiceMany(array('model' => 'UllPermission', 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'UllFlowApp', 'column' => array('slug')))
    );

    $this->widgetSchema->setNameFormat('ull_flow_app[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UllFlowApp';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['ull_permission_list']))
    {
      $this->setDefault('ull_permission_list', $this->object->UllPermission->getPrimaryKeys());
    }

  }

  protected function doSave($con = null)
  {
    parent::doSave($con);

    $this->saveUllPermissionList($con);
  }

  public function saveUllPermissionList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['ull_permission_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (is_null($con))
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->UllPermission->getPrimaryKeys();
    $values = $this->getValue('ull_permission_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('UllPermission', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('UllPermission', array_values($link));
    }
  }

}
