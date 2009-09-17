<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * UllProject filter form base class.
 *
 * @package    filters
 * @subpackage UllProject *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseUllProjectFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'namespace'       => new sfWidgetFormFilterInput(),
      'created_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'updated_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'creator_user_id' => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
      'updator_user_id' => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
      'slug'            => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'namespace'       => new sfValidatorPass(array('required' => false)),
      'created_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'updated_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'creator_user_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'UllUser', 'column' => 'id')),
      'updator_user_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'UllUser', 'column' => 'id')),
      'slug'            => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('ull_project_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UllProject';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'namespace'       => 'Text',
      'created_at'      => 'Date',
      'updated_at'      => 'Date',
      'creator_user_id' => 'ForeignKey',
      'updator_user_id' => 'ForeignKey',
      'slug'            => 'Text',
    );
  }
}