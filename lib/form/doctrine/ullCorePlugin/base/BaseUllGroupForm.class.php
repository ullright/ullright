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
      'id'                                 => new sfWidgetFormInputHidden(),
      'namespace'                          => new sfWidgetFormInput(),
      'first_name'                         => new sfWidgetFormInput(),
      'last_name'                          => new sfWidgetFormInput(),
      'display_name'                       => new sfWidgetFormInput(),
      'username'                           => new sfWidgetFormInput(),
      'email'                              => new sfWidgetFormInput(),
      'password'                           => new sfWidgetFormInput(),
      'sex'                                => new sfWidgetFormChoice(array('choices' => array('' => NULL, 'm' => 'm', 'f' => 'f'))),
      'entry_date'                         => new sfWidgetFormDate(),
      'deactivation_date'                  => new sfWidgetFormDate(),
      'separation_date'                    => new sfWidgetFormDate(),
      'ull_employment_type_id'             => new sfWidgetFormDoctrineChoice(array('model' => 'UllEmploymentType', 'add_empty' => true)),
      'ull_job_title_id'                   => new sfWidgetFormDoctrineChoice(array('model' => 'UllJobTitle', 'add_empty' => true)),
      'ull_company_id'                     => new sfWidgetFormDoctrineChoice(array('model' => 'UllCompany', 'add_empty' => true)),
      'ull_department_id'                  => new sfWidgetFormDoctrineChoice(array('model' => 'UllDepartment', 'add_empty' => true)),
      'ull_location_id'                    => new sfWidgetFormDoctrineChoice(array('model' => 'UllLocation', 'add_empty' => true)),
      'superior_ull_user_id'               => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
      'phone_extension'                    => new sfWidgetFormInput(),
      'is_show_extension_in_phonebook'     => new sfWidgetFormInputCheckbox(),
      'fax_extension'                      => new sfWidgetFormInput(),
      'is_show_fax_extension_in_phonebook' => new sfWidgetFormInputCheckbox(),
      'comment'                            => new sfWidgetFormTextarea(),
      'ull_user_status_id'                 => new sfWidgetFormDoctrineChoice(array('model' => 'UllUserStatus', 'add_empty' => false)),
      'is_virtual_group'                   => new sfWidgetFormInputCheckbox(),
      'photo'                              => new sfWidgetFormInput(),
      'parent_ull_user_id'                 => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
      'type'                               => new sfWidgetFormInput(),
      'created_at'                         => new sfWidgetFormDateTime(),
      'updated_at'                         => new sfWidgetFormDateTime(),
      'creator_user_id'                    => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
      'updator_user_id'                    => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
      'version'                            => new sfWidgetFormInput(),
      'ull_user_list'                      => new sfWidgetFormDoctrineChoiceMany(array('model' => 'UllUser')),
      'ull_permissions_list'               => new sfWidgetFormDoctrineChoiceMany(array('model' => 'UllPermission')),
    ));

    $this->setValidators(array(
      'id'                                 => new sfValidatorDoctrineChoice(array('model' => 'UllGroup', 'column' => 'id', 'required' => false)),
      'namespace'                          => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'first_name'                         => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'last_name'                          => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'display_name'                       => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'username'                           => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'email'                              => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'password'                           => new sfValidatorString(array('max_length' => 40, 'required' => false)),
      'sex'                                => new sfValidatorChoice(array('choices' => array('' => NULL, 'm' => 'm', 'f' => 'f'), 'required' => false)),
      'entry_date'                         => new sfValidatorDate(array('required' => false)),
      'deactivation_date'                  => new sfValidatorDate(array('required' => false)),
      'separation_date'                    => new sfValidatorDate(array('required' => false)),
      'ull_employment_type_id'             => new sfValidatorDoctrineChoice(array('model' => 'UllEmploymentType', 'required' => false)),
      'ull_job_title_id'                   => new sfValidatorDoctrineChoice(array('model' => 'UllJobTitle', 'required' => false)),
      'ull_company_id'                     => new sfValidatorDoctrineChoice(array('model' => 'UllCompany', 'required' => false)),
      'ull_department_id'                  => new sfValidatorDoctrineChoice(array('model' => 'UllDepartment', 'required' => false)),
      'ull_location_id'                    => new sfValidatorDoctrineChoice(array('model' => 'UllLocation', 'required' => false)),
      'superior_ull_user_id'               => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
      'phone_extension'                    => new sfValidatorInteger(array('required' => false)),
      'is_show_extension_in_phonebook'     => new sfValidatorBoolean(array('required' => false)),
      'fax_extension'                      => new sfValidatorInteger(array('required' => false)),
      'is_show_fax_extension_in_phonebook' => new sfValidatorBoolean(array('required' => false)),
      'comment'                            => new sfValidatorString(array('max_length' => 4000, 'required' => false)),
      'ull_user_status_id'                 => new sfValidatorDoctrineChoice(array('model' => 'UllUserStatus')),
      'is_virtual_group'                   => new sfValidatorBoolean(array('required' => false)),
      'photo'                              => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'parent_ull_user_id'                 => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
      'type'                               => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'created_at'                         => new sfValidatorDateTime(array('required' => false)),
      'updated_at'                         => new sfValidatorDateTime(array('required' => false)),
      'creator_user_id'                    => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
      'updator_user_id'                    => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
      'version'                            => new sfValidatorInteger(array('required' => false)),
      'ull_user_list'                      => new sfValidatorDoctrineChoiceMany(array('model' => 'UllUser', 'required' => false)),
      'ull_permissions_list'               => new sfValidatorDoctrineChoiceMany(array('model' => 'UllPermission', 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'UllGroup', 'column' => array('username')))
    );

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
      $this->setDefault('ull_user_list', $this->object->UllUser->getPrimaryKeys());
    }

    if (isset($this->widgetSchema['ull_permissions_list']))
    {
      $this->setDefault('ull_permissions_list', $this->object->UllPermissions->getPrimaryKeys());
    }

  }

  protected function doSave($con = null)
  {
    parent::doSave($con);

    $this->saveUllUserList($con);
    $this->saveUllPermissionsList($con);
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

    $existing = $this->object->UllUser->getPrimaryKeys();
    $values = $this->getValue('ull_user_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('UllUser', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('UllUser', array_values($link));
    }
  }

  public function saveUllPermissionsList($con = null)
  {
    if (!$this->isValid())
    {
      throw $this->getErrorSchema();
    }

    if (!isset($this->widgetSchema['ull_permissions_list']))
    {
      // somebody has unset this widget
      return;
    }

    if (is_null($con))
    {
      $con = $this->getConnection();
    }

    $existing = $this->object->UllPermissions->getPrimaryKeys();
    $values = $this->getValue('ull_permissions_list');
    if (!is_array($values))
    {
      $values = array();
    }

    $unlink = array_diff($existing, $values);
    if (count($unlink))
    {
      $this->object->unlink('UllPermissions', array_values($unlink));
    }

    $link = array_diff($values, $existing);
    if (count($link))
    {
      $this->object->link('UllPermissions', array_values($link));
    }
  }

}
