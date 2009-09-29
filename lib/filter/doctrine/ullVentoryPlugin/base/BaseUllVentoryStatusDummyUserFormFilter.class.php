<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * UllVentoryStatusDummyUser filter form base class.
 *
 * @package    filters
 * @subpackage UllVentoryStatusDummyUser *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseUllVentoryStatusDummyUserFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'namespace'                          => new sfWidgetFormFilterInput(),
      'first_name'                         => new sfWidgetFormFilterInput(),
      'last_name'                          => new sfWidgetFormFilterInput(),
      'username'                           => new sfWidgetFormFilterInput(),
      'email'                              => new sfWidgetFormFilterInput(),
      'password'                           => new sfWidgetFormFilterInput(),
      'sex'                                => new sfWidgetFormChoice(array('choices' => array('' => NULL, 'm' => 'm', 'f' => 'f'))),
      'entry_date'                         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'deactivation_date'                  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'separation_date'                    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'ull_employment_type_id'             => new sfWidgetFormDoctrineChoice(array('model' => 'UllEmploymentType', 'add_empty' => true)),
      'ull_job_title_id'                   => new sfWidgetFormDoctrineChoice(array('model' => 'UllJobTitle', 'add_empty' => true)),
      'ull_company_id'                     => new sfWidgetFormDoctrineChoice(array('model' => 'UllCompany', 'add_empty' => true)),
      'ull_department_id'                  => new sfWidgetFormDoctrineChoice(array('model' => 'UllDepartment', 'add_empty' => true)),
      'ull_location_id'                    => new sfWidgetFormDoctrineChoice(array('model' => 'UllLocation', 'add_empty' => true)),
      'superior_ull_user_id'               => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
      'phone_extension'                    => new sfWidgetFormFilterInput(),
      'is_show_extension_in_phonebook'     => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'fax_extension'                      => new sfWidgetFormFilterInput(),
      'is_show_fax_extension_in_phonebook' => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'comment'                            => new sfWidgetFormFilterInput(),
      'ull_user_status_id'                 => new sfWidgetFormDoctrineChoice(array('model' => 'UllUserStatus', 'add_empty' => true)),
      'is_virtual_group'                   => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'photo'                              => new sfWidgetFormFilterInput(),
      'type'                               => new sfWidgetFormFilterInput(),
      'created_at'                         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'updated_at'                         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'creator_user_id'                    => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
      'updator_user_id'                    => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
      'version'                            => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'namespace'                          => new sfValidatorPass(array('required' => false)),
      'first_name'                         => new sfValidatorPass(array('required' => false)),
      'last_name'                          => new sfValidatorPass(array('required' => false)),
      'username'                           => new sfValidatorPass(array('required' => false)),
      'email'                              => new sfValidatorPass(array('required' => false)),
      'password'                           => new sfValidatorPass(array('required' => false)),
      'sex'                                => new sfValidatorChoice(array('required' => false, 'choices' => array('' => NULL, 'm' => 'm', 'f' => 'f'))),
      'entry_date'                         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'deactivation_date'                  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'separation_date'                    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'ull_employment_type_id'             => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'UllEmploymentType', 'column' => 'id')),
      'ull_job_title_id'                   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'UllJobTitle', 'column' => 'id')),
      'ull_company_id'                     => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'UllCompany', 'column' => 'id')),
      'ull_department_id'                  => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'UllDepartment', 'column' => 'id')),
      'ull_location_id'                    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'UllLocation', 'column' => 'id')),
      'superior_ull_user_id'               => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'UllUser', 'column' => 'id')),
      'phone_extension'                    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_show_extension_in_phonebook'     => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'fax_extension'                      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_show_fax_extension_in_phonebook' => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'comment'                            => new sfValidatorPass(array('required' => false)),
      'ull_user_status_id'                 => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'UllUserStatus', 'column' => 'id')),
      'is_virtual_group'                   => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'photo'                              => new sfValidatorPass(array('required' => false)),
      'type'                               => new sfValidatorPass(array('required' => false)),
      'created_at'                         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'updated_at'                         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'creator_user_id'                    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'UllUser', 'column' => 'id')),
      'updator_user_id'                    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'UllUser', 'column' => 'id')),
      'version'                            => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('ull_ventory_status_dummy_user_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UllVentoryStatusDummyUser';
  }

  public function getFields()
  {
    return array(
      'id'                                 => 'Number',
      'namespace'                          => 'Text',
      'first_name'                         => 'Text',
      'last_name'                          => 'Text',
      'username'                           => 'Text',
      'email'                              => 'Text',
      'password'                           => 'Text',
      'sex'                                => 'Enum',
      'entry_date'                         => 'Date',
      'deactivation_date'                  => 'Date',
      'separation_date'                    => 'Date',
      'ull_employment_type_id'             => 'ForeignKey',
      'ull_job_title_id'                   => 'ForeignKey',
      'ull_company_id'                     => 'ForeignKey',
      'ull_department_id'                  => 'ForeignKey',
      'ull_location_id'                    => 'ForeignKey',
      'superior_ull_user_id'               => 'ForeignKey',
      'phone_extension'                    => 'Number',
      'is_show_extension_in_phonebook'     => 'Boolean',
      'fax_extension'                      => 'Number',
      'is_show_fax_extension_in_phonebook' => 'Boolean',
      'comment'                            => 'Text',
      'ull_user_status_id'                 => 'ForeignKey',
      'is_virtual_group'                   => 'Boolean',
      'photo'                              => 'Text',
      'type'                               => 'Text',
      'created_at'                         => 'Date',
      'updated_at'                         => 'Date',
      'creator_user_id'                    => 'ForeignKey',
      'updator_user_id'                    => 'ForeignKey',
      'version'                            => 'Number',
    );
  }
}