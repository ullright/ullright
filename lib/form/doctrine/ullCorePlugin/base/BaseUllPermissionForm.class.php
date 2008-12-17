<?php

/**
 * UllPermission form base class.
 *
 * @package    form
 * @subpackage ull_permission
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseUllPermissionForm extends BaseFormDoctrine
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
      'ull_group_list'    => new sfWidgetFormDoctrineSelectMany(array('model' => 'UllGroup')),
      'ull_flow_app_list' => new sfWidgetFormDoctrineSelectMany(array('model' => 'UllFlowApp')),
    ));

    $this->setValidators(array(
      'id'                           => new sfValidatorDoctrineChoice(array('model' => 'UllPermission', 'column' => 'id', 'required' => false)),
      'namespace'                    => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'slug'                         => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'created_at'                   => new sfValidatorDateTime(array('required' => false)),
      'updated_at'                   => new sfValidatorDateTime(array('required' => false)),
      'creator_user_id'              => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
      'updator_user_id'              => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
      'ull_group_list'    => new sfValidatorDoctrineChoiceMany(array('model' => 'UllGroup', 'required' => false)),
      'ull_flow_app_list' => new sfValidatorDoctrineChoiceMany(array('model' => 'UllFlowApp', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('ull_permission[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UllPermission';
  }

  public function updateDefaultsFromObject()
  {
    parent::updateDefaultsFromObject();

    if (isset($this->widgetSchema['ull_group_list']))
    {
      $values = array();
      foreach ($this->object->UllGroup as $obj)
      {
        $values[] = current($obj->identifier());
      }
      $this->object->clearRelated('UllGroup');
      $this->setDefault('ull_group_list', $values);
    }

    if (isset($this->widgetSchema['ull_flow_app_list']))
    {
      $values = array();
      foreach ($this->object->UllFlowApp as $obj)
      {
        $values[] = current($obj->identifier());
      }
      $this->object->clearRelated('UllFlowApp');
      $this->setDefault('ull_flow_app_list', $values);
    }

  }

  protected function doSave($con = null)
  {
    parent::doSave($con);

    $this->saveUllGroupList($con);
    $this->saveUllFlowAppList($con);
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

    $q = Doctrine_Query::create()
          ->delete()
          ->from('UllGroupPermission r')
          ->where('r.ull_permission_id = ?', current($this->object->identifier()))
          ->execute();

    $values = $this->getValue('ull_group_list');
    if (is_array($values))
    {
      foreach ($values as $value)
      {
        $obj = new UllGroupPermission();
        $obj->ull_permission_id = current($this->object->identifier());
        $obj->ull_group_id = $value;
        $obj->save();
      }
    }
  }

  public function saveUllFlowAppList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['ull_flow_app_list']))
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
          ->where('r.ull_permission_id = ?', current($this->object->identifier()))
          ->execute();

    $values = $this->getValue('ull_flow_app_list');
    if (is_array($values))
    {
      foreach ($values as $value)
      {
        $obj = new UllFlowAppPermission();
        $obj->ull_permission_id = current($this->object->identifier());
        $obj->ull_flow_app_id = $value;
        $obj->save();
      }
    }
  }

}