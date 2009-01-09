<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * UllFlowAppPermission filter form base class.
 *
 * @package    filters
 * @subpackage UllFlowAppPermission *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseUllFlowAppPermissionFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'namespace'         => new sfWidgetFormFilterInput(),
      'ull_flow_app_id'   => new sfWidgetFormDoctrineChoice(array('model' => 'UllFlowApp', 'add_empty' => true)),
      'ull_permission_id' => new sfWidgetFormDoctrineChoice(array('model' => 'UllPermission', 'add_empty' => true)),
      'created_at'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'updated_at'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'creator_user_id'   => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
      'updator_user_id'   => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'namespace'         => new sfValidatorPass(array('required' => false)),
      'ull_flow_app_id'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'UllFlowApp', 'column' => 'id')),
      'ull_permission_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'UllPermission', 'column' => 'id')),
      'created_at'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'updated_at'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'creator_user_id'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'UllUser', 'column' => 'id')),
      'updator_user_id'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'UllUser', 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('ull_flow_app_permission_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UllFlowAppPermission';
  }

  public function getFields()
  {
    return array(
      'id'                => 'Number',
      'namespace'         => 'Text',
      'ull_flow_app_id'   => 'ForeignKey',
      'ull_permission_id' => 'ForeignKey',
      'created_at'        => 'Date',
      'updated_at'        => 'Date',
      'creator_user_id'   => 'ForeignKey',
      'updator_user_id'   => 'ForeignKey',
    );
  }
}