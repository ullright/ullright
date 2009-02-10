<?php

require_once(sfConfig::get('sf_lib_dir').'/filter/doctrine/BaseFormFilterDoctrine.class.php');

/**
 * UllLocation filter form base class.
 *
 * @package    filters
 * @subpackage UllLocation *
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 11675 2008-09-19 15:21:38Z fabien $
 */
class BaseUllLocationFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'namespace'               => new sfWidgetFormFilterInput(),
      'name'                    => new sfWidgetFormFilterInput(),
      'short_name'              => new sfWidgetFormFilterInput(),
      'street'                  => new sfWidgetFormFilterInput(),
      'city'                    => new sfWidgetFormFilterInput(),
      'country'                 => new sfWidgetFormFilterInput(),
      'post_code'               => new sfWidgetFormFilterInput(),
      'phone_base_no'           => new sfWidgetFormFilterInput(),
      'phone_default_extension' => new sfWidgetFormFilterInput(),
      'fax_base_no'             => new sfWidgetFormFilterInput(),
      'fax_default_extension'   => new sfWidgetFormFilterInput(),
      'created_at'              => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'updated_at'              => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => true)),
      'creator_user_id'         => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
      'updator_user_id'         => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'namespace'               => new sfValidatorPass(array('required' => false)),
      'name'                    => new sfValidatorPass(array('required' => false)),
      'short_name'              => new sfValidatorPass(array('required' => false)),
      'street'                  => new sfValidatorPass(array('required' => false)),
      'city'                    => new sfValidatorPass(array('required' => false)),
      'country'                 => new sfValidatorPass(array('required' => false)),
      'post_code'               => new sfValidatorPass(array('required' => false)),
      'phone_base_no'           => new sfValidatorPass(array('required' => false)),
      'phone_default_extension' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'fax_base_no'             => new sfValidatorPass(array('required' => false)),
      'fax_default_extension'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'              => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'updated_at'              => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDate(array('required' => false)))),
      'creator_user_id'         => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'UllUser', 'column' => 'id')),
      'updator_user_id'         => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'UllUser', 'column' => 'id')),
    ));

    $this->widgetSchema->setNameFormat('ull_location_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UllLocation';
  }

  public function getFields()
  {
    return array(
      'id'                      => 'Number',
      'namespace'               => 'Text',
      'name'                    => 'Text',
      'short_name'              => 'Text',
      'street'                  => 'Text',
      'city'                    => 'Text',
      'country'                 => 'Text',
      'post_code'               => 'Text',
      'phone_base_no'           => 'Text',
      'phone_default_extension' => 'Number',
      'fax_base_no'             => 'Text',
      'fax_default_extension'   => 'Number',
      'created_at'              => 'Date',
      'updated_at'              => 'Date',
      'creator_user_id'         => 'ForeignKey',
      'updator_user_id'         => 'ForeignKey',
    );
  }
}