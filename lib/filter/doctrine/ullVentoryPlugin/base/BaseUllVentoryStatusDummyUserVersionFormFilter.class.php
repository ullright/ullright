<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * UllVentoryStatusDummyUserVersion filter form base class.
 *
 * @package    filters
 * @subpackage UllVentoryStatusDummyUserVersion *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseUllVentoryStatusDummyUserVersionFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'namespace'                          => new sfWidgetFormFilterInput(),
      'first_name'                         => new sfWidgetFormFilterInput(),
      'last_name'                          => new sfWidgetFormFilterInput(),
      'display_name'                       => new sfWidgetFormFilterInput(),
      'username'                           => new sfWidgetFormFilterInput(),
      'email'                              => new sfWidgetFormFilterInput(),
      'password'                           => new sfWidgetFormFilterInput(),
      'sex'                                => new sfWidgetFormChoice(array('choices' => array('' => NULL, 'm' => 'm', 'f' => 'f'))),
      'entry_date'                         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'deactivation_date'                  => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'separation_date'                    => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'ull_employment_type_id'             => new sfWidgetFormFilterInput(),
      'ull_job_title_id'                   => new sfWidgetFormFilterInput(),
      'ull_company_id'                     => new sfWidgetFormFilterInput(),
      'ull_department_id'                  => new sfWidgetFormFilterInput(),
      'ull_location_id'                    => new sfWidgetFormFilterInput(),
      'superior_ull_user_id'               => new sfWidgetFormFilterInput(),
      'phone_extension'                    => new sfWidgetFormFilterInput(),
      'is_show_extension_in_phonebook'     => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'fax_extension'                      => new sfWidgetFormFilterInput(),
      'is_show_fax_extension_in_phonebook' => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'comment'                            => new sfWidgetFormFilterInput(),
      'ull_user_status_id'                 => new sfWidgetFormFilterInput(),
      'is_virtual_group'                   => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'photo'                              => new sfWidgetFormFilterInput(),
      'type'                               => new sfWidgetFormFilterInput(),
      'created_at'                         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'updated_at'                         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'creator_user_id'                    => new sfWidgetFormFilterInput(),
      'updator_user_id'                    => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
      'reference_version'                  => new sfWidgetFormFilterInput(),
      'scheduled_update_date'              => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'done_at'                            => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
    ));

    $this->setValidators(array(
      'namespace'                          => new sfValidatorPass(array('required' => false)),
      'first_name'                         => new sfValidatorPass(array('required' => false)),
      'last_name'                          => new sfValidatorPass(array('required' => false)),
      'display_name'                       => new sfValidatorPass(array('required' => false)),
      'username'                           => new sfValidatorPass(array('required' => false)),
      'email'                              => new sfValidatorPass(array('required' => false)),
      'password'                           => new sfValidatorPass(array('required' => false)),
      'sex'                                => new sfValidatorChoice(array('required' => false, 'choices' => array('' => NULL, 'm' => 'm', 'f' => 'f'))),
      'entry_date'                         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'deactivation_date'                  => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'separation_date'                    => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'ull_employment_type_id'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'ull_job_title_id'                   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'ull_company_id'                     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'ull_department_id'                  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'ull_location_id'                    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'superior_ull_user_id'               => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'phone_extension'                    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_show_extension_in_phonebook'     => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'fax_extension'                      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_show_fax_extension_in_phonebook' => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'comment'                            => new sfValidatorPass(array('required' => false)),
      'ull_user_status_id'                 => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'is_virtual_group'                   => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'photo'                              => new sfValidatorPass(array('required' => false)),
      'type'                               => new sfValidatorPass(array('required' => false)),
      'created_at'                         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'updated_at'                         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'creator_user_id'                    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'updator_user_id'                    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'UllUser', 'column' => 'id')),
      'reference_version'                  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'scheduled_update_date'              => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'done_at'                            => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
    ));

    $this->widgetSchema->setNameFormat('ull_ventory_status_dummy_user_version_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UllVentoryStatusDummyUserVersion';
  }

  public function getFields()
  {
    return array(
      'id'                                 => 'Number',
      'namespace'                          => 'Text',
      'first_name'                         => 'Text',
      'last_name'                          => 'Text',
      'display_name'                       => 'Text',
      'username'                           => 'Text',
      'email'                              => 'Text',
      'password'                           => 'Text',
      'sex'                                => 'Enum',
      'entry_date'                         => 'Date',
      'deactivation_date'                  => 'Date',
      'separation_date'                    => 'Date',
      'ull_employment_type_id'             => 'Number',
      'ull_job_title_id'                   => 'Number',
      'ull_company_id'                     => 'Number',
      'ull_department_id'                  => 'Number',
      'ull_location_id'                    => 'Number',
      'superior_ull_user_id'               => 'Number',
      'phone_extension'                    => 'Number',
      'is_show_extension_in_phonebook'     => 'Boolean',
      'fax_extension'                      => 'Number',
      'is_show_fax_extension_in_phonebook' => 'Boolean',
      'comment'                            => 'Text',
      'ull_user_status_id'                 => 'Number',
      'is_virtual_group'                   => 'Boolean',
      'photo'                              => 'Text',
      'type'                               => 'Text',
      'created_at'                         => 'Date',
      'updated_at'                         => 'Date',
      'creator_user_id'                    => 'Number',
      'updator_user_id'                    => 'ForeignKey',
      'version'                            => 'Number',
      'reference_version'                  => 'Number',
      'scheduled_update_date'              => 'Date',
      'done_at'                            => 'Date',
    );
  }
}