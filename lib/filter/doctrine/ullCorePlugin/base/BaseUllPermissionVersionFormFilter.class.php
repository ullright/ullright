<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * UllPermissionVersion filter form base class.
 *
 * @package    filters
 * @subpackage UllPermissionVersion *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseUllPermissionVersionFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'namespace'             => new sfWidgetFormFilterInput(),
      'slug'                  => new sfWidgetFormFilterInput(),
      'created_at'            => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'updated_at'            => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'creator_user_id'       => new sfWidgetFormFilterInput(),
      'updator_user_id'       => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
      'reference_version'     => new sfWidgetFormFilterInput(),
      'scheduled_update_date' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'done_at'               => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
    ));

    $this->setValidators(array(
      'namespace'             => new sfValidatorPass(array('required' => false)),
      'slug'                  => new sfValidatorPass(array('required' => false)),
      'created_at'            => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'updated_at'            => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'creator_user_id'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'updator_user_id'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'UllUser', 'column' => 'id')),
      'reference_version'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'scheduled_update_date' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'done_at'               => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
    ));

    $this->widgetSchema->setNameFormat('ull_permission_version_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UllPermissionVersion';
  }

  public function getFields()
  {
    return array(
      'id'                    => 'Number',
      'namespace'             => 'Text',
      'slug'                  => 'Text',
      'created_at'            => 'Date',
      'updated_at'            => 'Date',
      'creator_user_id'       => 'Number',
      'updator_user_id'       => 'ForeignKey',
      'version'               => 'Number',
      'reference_version'     => 'Number',
      'scheduled_update_date' => 'Date',
      'done_at'               => 'Date',
    );
  }
}