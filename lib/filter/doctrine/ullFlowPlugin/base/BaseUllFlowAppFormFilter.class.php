<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * UllFlowApp filter form base class.
 *
 * @package    filters
 * @subpackage UllFlowApp *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseUllFlowAppFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'namespace'           => new sfWidgetFormFilterInput(),
      'slug'                => new sfWidgetFormFilterInput(),
      'label'               => new sfWidgetFormFilterInput(),
      'doc_label'           => new sfWidgetFormFilterInput(),
      'created_at'          => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'updated_at'          => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'creator_user_id'     => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
      'updator_user_id'     => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
      'ull_permission_list' => new sfWidgetFormDoctrineSelectMany(array('model' => 'UllPermission')),
    ));

    $this->setValidators(array(
      'namespace'           => new sfValidatorPass(array('required' => false)),
      'slug'                => new sfValidatorPass(array('required' => false)),
      'label'               => new sfValidatorPass(array('required' => false)),
      'doc_label'           => new sfValidatorPass(array('required' => false)),
      'created_at'          => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'updated_at'          => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'creator_user_id'     => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'UllUser', 'column' => 'id')),
      'updator_user_id'     => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'UllUser', 'column' => 'id')),
      'ull_permission_list' => new sfValidatorDoctrineChoiceMany(array('model' => 'UllPermission', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('ull_flow_app_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function addUllPermissionListColumnQuery(Doctrine_Query $query, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $query->leftJoin('r.UllFlowAppPermission UllFlowAppPermission')
          ->andWhereIn('UllFlowAppPermission.ull_permission_id', $values);
  }

  public function getModelName()
  {
    return 'UllFlowApp';
  }

  public function getFields()
  {
    return array(
      'id'                  => 'Number',
      'namespace'           => 'Text',
      'slug'                => 'Text',
      'label'               => 'Text',
      'doc_label'           => 'Text',
      'created_at'          => 'Date',
      'updated_at'          => 'Date',
      'creator_user_id'     => 'ForeignKey',
      'updator_user_id'     => 'ForeignKey',
      'ull_permission_list' => 'ManyKey',
    );
  }
}