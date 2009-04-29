<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * TestTable filter form base class.
 *
 * @package    filters
 * @subpackage TestTable *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseTestTableFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'namespace'         => new sfWidgetFormFilterInput(),
      'my_boolean'        => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'my_email'          => new sfWidgetFormFilterInput(),
      'my_select_box'     => new sfWidgetFormFilterInput(),
      'my_useless_column' => new sfWidgetFormFilterInput(),
      'ull_user_id'       => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
      'created_at'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'updated_at'        => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'creator_user_id'   => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
      'updator_user_id'   => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'namespace'         => new sfValidatorPass(array('required' => false)),
      'my_boolean'        => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'my_email'          => new sfValidatorPass(array('required' => false)),
      'my_select_box'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'my_useless_column' => new sfValidatorPass(array('required' => false)),
      'ull_user_id'       => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'UllUser', 'column' => 'id')),
      'created_at'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'updated_at'        => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'creator_user_id'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'UllUser', 'column' => 'id')),
      'updator_user_id'   => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'UllUser', 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('test_table_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'TestTable';
  }

  public function getFields()
  {
    return array(
      'id'                => 'Number',
      'namespace'         => 'Text',
      'my_boolean'        => 'Boolean',
      'my_email'          => 'Text',
      'my_select_box'     => 'Number',
      'my_useless_column' => 'Text',
      'ull_user_id'       => 'ForeignKey',
      'created_at'        => 'Date',
      'updated_at'        => 'Date',
      'creator_user_id'   => 'ForeignKey',
      'updator_user_id'   => 'ForeignKey',
    );
  }
}