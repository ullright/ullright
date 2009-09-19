<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * UllTimeReporting filter form base class.
 *
 * @package    filters
 * @subpackage UllTimeReporting *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseUllTimeReportingFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'namespace'           => new sfWidgetFormFilterInput(),
      'ull_user_id'         => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
      'date'                => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'begin_work_at'       => new sfWidgetFormFilterInput(),
      'end_work_at'         => new sfWidgetFormFilterInput(),
      'begin_break1_at'     => new sfWidgetFormFilterInput(),
      'end_break1_at'       => new sfWidgetFormFilterInput(),
      'begin_break2_at'     => new sfWidgetFormFilterInput(),
      'end_break2_at'       => new sfWidgetFormFilterInput(),
      'begin_break3_at'     => new sfWidgetFormFilterInput(),
      'end_break3_at'       => new sfWidgetFormFilterInput(),
      'total_work_seconds'  => new sfWidgetFormFilterInput(),
      'total_break_seconds' => new sfWidgetFormFilterInput(),
      'created_at'          => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'updated_at'          => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'creator_user_id'     => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
      'updator_user_id'     => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'namespace'           => new sfValidatorPass(array('required' => false)),
      'ull_user_id'         => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'UllUser', 'column' => 'id')),
      'date'                => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'begin_work_at'       => new sfValidatorPass(array('required' => false)),
      'end_work_at'         => new sfValidatorPass(array('required' => false)),
      'begin_break1_at'     => new sfValidatorPass(array('required' => false)),
      'end_break1_at'       => new sfValidatorPass(array('required' => false)),
      'begin_break2_at'     => new sfValidatorPass(array('required' => false)),
      'end_break2_at'       => new sfValidatorPass(array('required' => false)),
      'begin_break3_at'     => new sfValidatorPass(array('required' => false)),
      'end_break3_at'       => new sfValidatorPass(array('required' => false)),
      'total_work_seconds'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'total_break_seconds' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'          => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'updated_at'          => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'creator_user_id'     => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'UllUser', 'column' => 'id')),
      'updator_user_id'     => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'UllUser', 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('ull_time_reporting_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UllTimeReporting';
  }

  public function getFields()
  {
    return array(
      'id'                  => 'Number',
      'namespace'           => 'Text',
      'ull_user_id'         => 'ForeignKey',
      'date'                => 'Date',
      'begin_work_at'       => 'Text',
      'end_work_at'         => 'Text',
      'begin_break1_at'     => 'Text',
      'end_break1_at'       => 'Text',
      'begin_break2_at'     => 'Text',
      'end_break2_at'       => 'Text',
      'begin_break3_at'     => 'Text',
      'end_break3_at'       => 'Text',
      'total_work_seconds'  => 'Number',
      'total_break_seconds' => 'Number',
      'created_at'          => 'Date',
      'updated_at'          => 'Date',
      'creator_user_id'     => 'ForeignKey',
      'updator_user_id'     => 'ForeignKey',
    );
  }
}