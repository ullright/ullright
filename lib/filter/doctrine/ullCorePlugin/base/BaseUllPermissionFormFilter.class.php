<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * UllPermission filter form base class.
 *
 * @package    filters
 * @subpackage UllPermission *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseUllPermissionFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'namespace'         => new sfWidgetFormFilterInput(),
      'slug'              => new sfWidgetFormFilterInput(),
      'created_at'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'updated_at'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'creator_user_id'   => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
      'updator_user_id'   => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
      'ull_group_list'    => new sfWidgetFormDoctrineChoiceMany(array('model' => 'UllGroup')),
      'ull_flow_app_list' => new sfWidgetFormDoctrineChoiceMany(array('model' => 'UllFlowApp')),
    ));

    $this->setValidators(array(
      'namespace'         => new sfValidatorPass(array('required' => false)),
      'slug'              => new sfValidatorPass(array('required' => false)),
      'created_at'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'updated_at'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'creator_user_id'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'UllUser', 'column' => 'id')),
      'updator_user_id'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'UllUser', 'column' => 'id')),
      'ull_group_list'    => new sfValidatorDoctrineChoiceMany(array('model' => 'UllGroup', 'required' => false)),
      'ull_flow_app_list' => new sfValidatorDoctrineChoiceMany(array('model' => 'UllFlowApp', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('ull_permission_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function addUllGroupListColumnQuery(Doctrine_Query $query, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $query->leftJoin('r.UllGroupPermission UllGroupPermission')
          ->andWhereIn('UllGroupPermission.ull_group_id', $values);
  }

  public function addUllFlowAppListColumnQuery(Doctrine_Query $query, $field, $values)
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
          ->andWhereIn('UllFlowAppPermission.ull_flow_app_id', $values);
  }

  public function getModelName()
  {
    return 'UllPermission';
  }

  public function getFields()
  {
    return array(
      'id'                => 'Number',
      'namespace'         => 'Text',
      'slug'              => 'Text',
      'created_at'        => 'Date',
      'updated_at'        => 'Date',
      'creator_user_id'   => 'ForeignKey',
      'updator_user_id'   => 'ForeignKey',
      'ull_group_list'    => 'ManyKey',
      'ull_flow_app_list' => 'ManyKey',
    );
  }
}