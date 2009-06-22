<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * UllVentoryItemMemory filter form base class.
 *
 * @package    filters
 * @subpackage UllVentoryItemMemory *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseUllVentoryItemMemoryFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'namespace'           => new sfWidgetFormFilterInput(),
      'ull_ventory_item_id' => new sfWidgetFormDoctrineChoice(array('model' => 'UllVentoryItem', 'add_empty' => true)),
      'transfer_at'         => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'source_ull_user_id'  => new sfWidgetFormFilterInput(),
      'target_ull_user_id'  => new sfWidgetFormFilterInput(),
      'comment'             => new sfWidgetFormFilterInput(),
      'created_at'          => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'updated_at'          => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'creator_user_id'     => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
      'updator_user_id'     => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'namespace'           => new sfValidatorPass(array('required' => false)),
      'ull_ventory_item_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'UllVentoryItem', 'column' => 'id')),
      'transfer_at'         => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'source_ull_user_id'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'target_ull_user_id'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'comment'             => new sfValidatorPass(array('required' => false)),
      'created_at'          => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'updated_at'          => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'creator_user_id'     => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'UllUser', 'column' => 'id')),
      'updator_user_id'     => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'UllUser', 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('ull_ventory_item_memory_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UllVentoryItemMemory';
  }

  public function getFields()
  {
    return array(
      'id'                  => 'Number',
      'namespace'           => 'Text',
      'ull_ventory_item_id' => 'ForeignKey',
      'transfer_at'         => 'Date',
      'source_ull_user_id'  => 'Number',
      'target_ull_user_id'  => 'Number',
      'comment'             => 'Text',
      'created_at'          => 'Date',
      'updated_at'          => 'Date',
      'creator_user_id'     => 'ForeignKey',
      'updator_user_id'     => 'ForeignKey',
    );
  }
}