<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * UllFlowColumnConfig filter form base class.
 *
 * @package    filters
 * @subpackage UllFlowColumnConfig *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseUllFlowColumnConfigFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'namespace'          => new sfWidgetFormFilterInput(),
      'ull_flow_app_id'    => new sfWidgetFormDoctrineChoice(array('model' => 'UllFlowApp', 'add_empty' => true)),
      'slug'               => new sfWidgetFormFilterInput(),
      'label'              => new sfWidgetFormFilterInput(),
      'sequence'           => new sfWidgetFormFilterInput(),
      'ull_column_type_id' => new sfWidgetFormDoctrineChoice(array('model' => 'UllColumnType', 'add_empty' => true)),
      'options'            => new sfWidgetFormFilterInput(),
      'is_enabled'         => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_in_list'         => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_mandatory'       => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_subject'         => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'default_value'      => new sfWidgetFormFilterInput(),
      'created_at'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'updated_at'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'creator_user_id'    => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
      'updator_user_id'    => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'namespace'          => new sfValidatorPass(array('required' => false)),
      'ull_flow_app_id'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'UllFlowApp', 'column' => 'id')),
      'slug'               => new sfValidatorPass(array('required' => false)),
      'label'              => new sfValidatorPass(array('required' => false)),
      'sequence'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'ull_column_type_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'UllColumnType', 'column' => 'id')),
      'options'            => new sfValidatorPass(array('required' => false)),
      'is_enabled'         => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_in_list'         => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_mandatory'       => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_subject'         => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'default_value'      => new sfValidatorPass(array('required' => false)),
      'created_at'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'updated_at'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'creator_user_id'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'UllUser', 'column' => 'id')),
      'updator_user_id'    => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'UllUser', 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('ull_flow_column_config_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UllFlowColumnConfig';
  }

  public function getFields()
  {
    return array(
      'id'                 => 'Number',
      'namespace'          => 'Text',
      'ull_flow_app_id'    => 'ForeignKey',
      'slug'               => 'Text',
      'label'              => 'Text',
      'sequence'           => 'Number',
      'ull_column_type_id' => 'ForeignKey',
      'options'            => 'Text',
      'is_enabled'         => 'Boolean',
      'is_in_list'         => 'Boolean',
      'is_mandatory'       => 'Boolean',
      'is_subject'         => 'Boolean',
      'default_value'      => 'Text',
      'created_at'         => 'Date',
      'updated_at'         => 'Date',
      'creator_user_id'    => 'ForeignKey',
      'updator_user_id'    => 'ForeignKey',
    );
  }
}