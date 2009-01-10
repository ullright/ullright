<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * UllFlowValue filter form base class.
 *
 * @package    filters
 * @subpackage UllFlowValue *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseUllFlowValueFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'namespace'                 => new sfWidgetFormFilterInput(),
      'ull_flow_doc_id'           => new sfWidgetFormDoctrineChoice(array('model' => 'UllFlowDoc', 'add_empty' => true)),
      'ull_flow_column_config_id' => new sfWidgetFormDoctrineChoice(array('model' => 'UllFlowColumnConfig', 'add_empty' => true)),
      'ull_flow_memory_id'        => new sfWidgetFormFilterInput(),
      'value'                     => new sfWidgetFormFilterInput(),
      'created_at'                => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'updated_at'                => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'creator_user_id'           => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
      'updator_user_id'           => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'namespace'                 => new sfValidatorPass(array('required' => false)),
      'ull_flow_doc_id'           => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'UllFlowDoc', 'column' => 'id')),
      'ull_flow_column_config_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'UllFlowColumnConfig', 'column' => 'id')),
      'ull_flow_memory_id'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'value'                     => new sfValidatorPass(array('required' => false)),
      'created_at'                => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'updated_at'                => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'creator_user_id'           => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'UllUser', 'column' => 'id')),
      'updator_user_id'           => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'UllUser', 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('ull_flow_value_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UllFlowValue';
  }

  public function getFields()
  {
    return array(
      'id'                        => 'Number',
      'namespace'                 => 'Text',
      'ull_flow_doc_id'           => 'ForeignKey',
      'ull_flow_column_config_id' => 'ForeignKey',
      'ull_flow_memory_id'        => 'Number',
      'value'                     => 'Text',
      'created_at'                => 'Date',
      'updated_at'                => 'Date',
      'creator_user_id'           => 'ForeignKey',
      'updator_user_id'           => 'ForeignKey',
    );
  }
}