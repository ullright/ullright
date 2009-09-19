<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * UllProjectReporting filter form base class.
 *
 * @package    filters
 * @subpackage UllProjectReporting *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseUllProjectReportingFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'namespace'        => new sfWidgetFormFilterInput(),
      'ull_user_id'      => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
      'date'             => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'ull_project_id'   => new sfWidgetFormDoctrineChoice(array('model' => 'UllProject', 'add_empty' => true)),
      'duration_seconds' => new sfWidgetFormFilterInput(),
      'comment'          => new sfWidgetFormFilterInput(),
      'week'             => new sfWidgetFormFilterInput(),
      'created_at'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'updated_at'       => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'creator_user_id'  => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
      'updator_user_id'  => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'namespace'        => new sfValidatorPass(array('required' => false)),
      'ull_user_id'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'UllUser', 'column' => 'id')),
      'date'             => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'ull_project_id'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'UllProject', 'column' => 'id')),
      'duration_seconds' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'comment'          => new sfValidatorPass(array('required' => false)),
      'week'             => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'updated_at'       => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'creator_user_id'  => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'UllUser', 'column' => 'id')),
      'updator_user_id'  => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'UllUser', 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('ull_project_reporting_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UllProjectReporting';
  }

  public function getFields()
  {
    return array(
      'id'               => 'Number',
      'namespace'        => 'Text',
      'ull_user_id'      => 'ForeignKey',
      'date'             => 'Date',
      'ull_project_id'   => 'ForeignKey',
      'duration_seconds' => 'Number',
      'comment'          => 'Text',
      'week'             => 'Number',
      'created_at'       => 'Date',
      'updated_at'       => 'Date',
      'creator_user_id'  => 'ForeignKey',
      'updator_user_id'  => 'ForeignKey',
    );
  }
}