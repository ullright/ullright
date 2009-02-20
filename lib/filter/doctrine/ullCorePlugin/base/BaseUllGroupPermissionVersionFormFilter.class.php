<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * UllGroupPermissionVersion filter form base class.
 *
 * @package    filters
 * @subpackage UllGroupPermissionVersion *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseUllGroupPermissionVersionFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'namespace'         => new sfWidgetFormFilterInput(),
      'ull_group_id'      => new sfWidgetFormFilterInput(),
      'ull_permission_id' => new sfWidgetFormFilterInput(),
      'created_at'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'updated_at'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'creator_user_id'   => new sfWidgetFormFilterInput(),
      'updator_user_id'   => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'namespace'         => new sfValidatorPass(array('required' => false)),
      'ull_group_id'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'ull_permission_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'updated_at'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'creator_user_id'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'updator_user_id'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('ull_group_permission_version_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UllGroupPermissionVersion';
  }

  public function getFields()
  {
    return array(
      'id'                => 'Number',
      'namespace'         => 'Text',
      'ull_group_id'      => 'Number',
      'ull_permission_id' => 'Number',
      'created_at'        => 'Date',
      'updated_at'        => 'Date',
      'creator_user_id'   => 'Number',
      'updator_user_id'   => 'Number',
      'version'           => 'Number',
    );
  }
}