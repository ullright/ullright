<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * UllGroup filter form base class.
 *
 * @package    filters
 * @subpackage UllGroup *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseUllGroupFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'namespace'            => new sfWidgetFormFilterInput(),
      'first_name'           => new sfWidgetFormFilterInput(),
      'last_name'            => new sfWidgetFormFilterInput(),
      'display_name'         => new sfWidgetFormFilterInput(),
      'username'             => new sfWidgetFormFilterInput(),
      'email'                => new sfWidgetFormFilterInput(),
      'password'             => new sfWidgetFormFilterInput(),
      'type'                 => new sfWidgetFormFilterInput(),
      'created_at'           => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'updated_at'           => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'creator_user_id'      => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
      'updator_user_id'      => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
      'ull_user_list'        => new sfWidgetFormDoctrineSelectMany(array('model' => 'UllUser')),
      'ull_permissions_list' => new sfWidgetFormDoctrineSelectMany(array('model' => 'UllPermission')),
    ));

    $this->setValidators(array(
      'namespace'            => new sfValidatorPass(array('required' => false)),
      'first_name'           => new sfValidatorPass(array('required' => false)),
      'last_name'            => new sfValidatorPass(array('required' => false)),
      'display_name'         => new sfValidatorPass(array('required' => false)),
      'username'             => new sfValidatorPass(array('required' => false)),
      'email'                => new sfValidatorPass(array('required' => false)),
      'password'             => new sfValidatorPass(array('required' => false)),
      'type'                 => new sfValidatorPass(array('required' => false)),
      'created_at'           => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'updated_at'           => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'creator_user_id'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'UllUser', 'column' => 'id')),
      'updator_user_id'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'UllUser', 'column' => 'id')),
      'ull_user_list'        => new sfValidatorDoctrineChoiceMany(array('model' => 'UllUser', 'required' => false)),
      'ull_permissions_list' => new sfValidatorDoctrineChoiceMany(array('model' => 'UllPermission', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('ull_group_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function addUllUserListColumnQuery(Doctrine_Query $query, $field, $values)
  {
    if (!is_array($values))
    {
      $values = array($values);
    }

    if (!count($values))
    {
      return;
    }

    $query->leftJoin('r.UllEntityGroup UllEntityGroup')
          ->andWhereIn('UllEntityGroup.ull_entity_id', $values);
  }

  public function addUllPermissionsListColumnQuery(Doctrine_Query $query, $field, $values)
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
          ->andWhereIn('UllGroupPermission.ull_permission_id', $values);
  }

  public function getModelName()
  {
    return 'UllGroup';
  }

  public function getFields()
  {
    return array(
      'id'                   => 'Number',
      'namespace'            => 'Text',
      'first_name'           => 'Text',
      'last_name'            => 'Text',
      'display_name'         => 'Text',
      'username'             => 'Text',
      'email'                => 'Text',
      'password'             => 'Text',
      'type'                 => 'Text',
      'created_at'           => 'Date',
      'updated_at'           => 'Date',
      'creator_user_id'      => 'ForeignKey',
      'updator_user_id'      => 'ForeignKey',
      'ull_user_list'        => 'ManyKey',
      'ull_permissions_list' => 'ManyKey',
    );
  }
}