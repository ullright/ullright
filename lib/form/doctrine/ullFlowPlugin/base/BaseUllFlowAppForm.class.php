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
      'id'                           => new sfWidgetFormInputHidden(),
      'namespace'                    => new sfWidgetFormInput(),
      'slug'                         => new sfWidgetFormInput(),
      'created_at'                   => new sfWidgetFormDateTime(),
      'updated_at'                   => new sfWidgetFormDateTime(),
      'creator_user_id'              => new sfWidgetFormDoctrineSelect(array('model' => 'UllUser', 'add_empty' => true)),
      'updator_user_id'              => new sfWidgetFormDoctrineSelect(array('model' => 'UllUser', 'add_empty' => true)),
      'ull_permission_list' => new sfWidgetFormDoctrineSelectMany(array('model' => 'UllPermission')),
    ));

    $this->setValidators(array(
      'id'                           => new sfValidatorDoctrineChoice(array('model' => 'UllFlowApp', 'column' => 'id', 'required' => false)),
      'namespace'                    => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'slug'                         => new sfValidatorString(array('max_length' => 32)),
      'created_at'                   => new sfValidatorDateTime(array('required' => false)),
      'updated_at'                   => new sfValidatorDateTime(array('required' => false)),
      'creator_user_id'              => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
      'updator_user_id'              => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
      'ull_permission_list' => new sfValidatorDoctrineChoiceMany(array('model' => 'UllPermission', 'required' => false)),
    ));

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
      $values = array();
      foreach ($this->object->UllPermission as $obj)
      {
        $values[] = current($obj->identifier());
      }
      $this->object->clearRelated('UllPermission');
      $this->setDefault('ull_permission_list', $values);
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

    $q = Doctrine_Query::create()
          ->delete()
          ->from('UllFlowAppPermission r')
          ->where('r.ull_flow_app_id = ?', current($this->object->identifier()))
          ->execute();

    $values = $this->getValue('ull_permission_list');
    if (is_array($values))
    {
      foreach ($values as $value)
      {
        $obj = new UllFlowAppPermission();
        $obj->ull_flow_app_id = current($this->object->identifier());
        $obj->ull_permission_id = $value;
        $obj->save();
      }
    }
  }

}