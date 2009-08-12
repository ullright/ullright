<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * UllVentorySoftwareLicense filter form base class.
 *
 * @package    filters
 * @subpackage UllVentorySoftwareLicense *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseUllVentorySoftwareLicenseFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'namespace'               => new sfWidgetFormFilterInput(),
      'ull_ventory_software_id' => new sfWidgetFormDoctrineChoice(array('model' => 'UllVentorySoftware', 'add_empty' => true)),
      'license_key'             => new sfWidgetFormFilterInput(),
      'quantity'                => new sfWidgetFormFilterInput(),
      'supplier'                => new sfWidgetFormFilterInput(),
      'delivery_date'           => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'comment'                 => new sfWidgetFormFilterInput(),
      'created_at'              => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'updated_at'              => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'creator_user_id'         => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
      'updator_user_id'         => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'namespace'               => new sfValidatorPass(array('required' => false)),
      'ull_ventory_software_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'UllVentorySoftware', 'column' => 'id')),
      'license_key'             => new sfValidatorPass(array('required' => false)),
      'quantity'                => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'supplier'                => new sfValidatorPass(array('required' => false)),
      'delivery_date'           => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'comment'                 => new sfValidatorPass(array('required' => false)),
      'created_at'              => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'updated_at'              => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'creator_user_id'         => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'UllUser', 'column' => 'id')),
      'updator_user_id'         => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'UllUser', 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('ull_ventory_software_license_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UllVentorySoftwareLicense';
  }

  public function getFields()
  {
    return array(
      'id'                      => 'Number',
      'namespace'               => 'Text',
      'ull_ventory_software_id' => 'ForeignKey',
      'license_key'             => 'Text',
      'quantity'                => 'Number',
      'supplier'                => 'Text',
      'delivery_date'           => 'Date',
      'comment'                 => 'Text',
      'created_at'              => 'Date',
      'updated_at'              => 'Date',
      'creator_user_id'         => 'ForeignKey',
      'updator_user_id'         => 'ForeignKey',
    );
  }
}