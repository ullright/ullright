<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * UllFlowAction filter form base class.
 *
 * @package    filters
 * @subpackage UllFlowAction *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseUllFlowActionFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'namespace'            => new sfWidgetFormFilterInput(),
      'is_status_only'       => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_enable_validation' => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_notify_creator'    => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_notify_next'       => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_in_resultlist'     => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_show_assigned_to'  => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'is_comment_mandatory' => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'created_at'           => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'updated_at'           => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'creator_user_id'      => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
      'updator_user_id'      => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
      'slug'                 => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'namespace'            => new sfValidatorPass(array('required' => false)),
      'is_status_only'       => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_enable_validation' => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_notify_creator'    => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_notify_next'       => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_in_resultlist'     => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_show_assigned_to'  => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'is_comment_mandatory' => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'created_at'           => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'updated_at'           => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'creator_user_id'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'UllUser', 'column' => 'id')),
      'updator_user_id'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'UllUser', 'column' => 'id')),
      'slug'                 => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('ull_flow_action_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UllFlowAction';
  }

  public function getFields()
  {
    return array(
      'id'                   => 'Number',
      'namespace'            => 'Text',
      'is_status_only'       => 'Boolean',
      'is_enable_validation' => 'Boolean',
      'is_notify_creator'    => 'Boolean',
      'is_notify_next'       => 'Boolean',
      'is_in_resultlist'     => 'Boolean',
      'is_show_assigned_to'  => 'Boolean',
      'is_comment_mandatory' => 'Boolean',
      'created_at'           => 'Date',
      'updated_at'           => 'Date',
      'creator_user_id'      => 'ForeignKey',
      'updator_user_id'      => 'ForeignKey',
      'slug'                 => 'Text',
    );
  }
}