<?php

/**
 * UllParentEntityVersion form base class.
 *
 * @package    form
 * @subpackage ull_parent_entity_version
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseUllParentEntityVersionForm extends BaseFormDoctrine
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
      'ull_employment_type_id'             => new sfWidgetFormInput(),
      'ull_job_title_id'                   => new sfWidgetFormInput(),
      'ull_company_id'                     => new sfWidgetFormInput(),
      'ull_department_id'                  => new sfWidgetFormInput(),
      'ull_location_id'                    => new sfWidgetFormInput(),
      'superior_ull_user_id'               => new sfWidgetFormInput(),
      'phone_extension'                    => new sfWidgetFormInput(),
      'is_show_extension_in_phonebook'     => new sfWidgetFormInputCheckbox(),
      'fax_extension'                      => new sfWidgetFormInput(),
      'is_show_fax_extension_in_phonebook' => new sfWidgetFormInputCheckbox(),
      'comment'                            => new sfWidgetFormTextarea(),
      'ull_user_status_id'                 => new sfWidgetFormInput(),
      'created_at'                         => new sfWidgetFormDateTime(),
      'updated_at'                         => new sfWidgetFormDateTime(),
      'creator_user_id'                    => new sfWidgetFormInput(),
      'updator_user_id'                    => new sfWidgetFormInput(),
      'version'                            => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'id'                                 => new sfValidatorDoctrineChoice(array('model' => 'UllParentEntityVersion', 'column' => 'id', 'required' => false)),
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
      'ull_employment_type_id'             => new sfValidatorInteger(array('required' => false)),
      'ull_job_title_id'                   => new sfValidatorInteger(array('required' => false)),
      'ull_company_id'                     => new sfValidatorInteger(array('required' => false)),
      'ull_department_id'                  => new sfValidatorInteger(array('required' => false)),
      'ull_location_id'                    => new sfValidatorInteger(array('required' => false)),
      'superior_ull_user_id'               => new sfValidatorInteger(array('required' => false)),
      'phone_extension'                    => new sfValidatorInteger(array('required' => false)),
      'is_show_extension_in_phonebook'     => new sfValidatorBoolean(array('required' => false)),
      'fax_extension'                      => new sfValidatorInteger(array('required' => false)),
      'is_show_fax_extension_in_phonebook' => new sfValidatorBoolean(array('required' => false)),
      'comment'                            => new sfValidatorString(array('max_length' => 4000, 'required' => false)),
      'ull_user_status_id'                 => new sfValidatorInteger(),
      'created_at'                         => new sfValidatorDateTime(array('required' => false)),
      'updated_at'                         => new sfValidatorDateTime(array('required' => false)),
      'creator_user_id'                    => new sfValidatorInteger(array('required' => false)),
      'updator_user_id'                    => new sfValidatorInteger(array('required' => false)),
      'version'                            => new sfValidatorDoctrineChoice(array('model' => 'UllParentEntityVersion', 'column' => 'version', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('ull_parent_entity_version[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UllParentEntityVersion';
  }

}