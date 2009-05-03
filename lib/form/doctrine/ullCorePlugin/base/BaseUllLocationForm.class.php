<?php

/**
 * UllLocation form base class.
 *
 * @package    form
 * @subpackage ull_location
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseUllLocationForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                      => new sfWidgetFormInputHidden(),
      'namespace'               => new sfWidgetFormInput(),
      'name'                    => new sfWidgetFormInput(),
      'short_name'              => new sfWidgetFormInput(),
      'street'                  => new sfWidgetFormInput(),
      'city'                    => new sfWidgetFormInput(),
      'country'                 => new sfWidgetFormInput(),
      'post_code'               => new sfWidgetFormInput(),
      'phone_base_no'           => new sfWidgetFormInput(),
      'phone_default_extension' => new sfWidgetFormInput(),
      'fax_base_no'             => new sfWidgetFormInput(),
      'fax_default_extension'   => new sfWidgetFormInput(),
      'created_at'              => new sfWidgetFormDateTime(),
      'updated_at'              => new sfWidgetFormDateTime(),
      'creator_user_id'         => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
      'updator_user_id'         => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'id'                      => new sfValidatorDoctrineChoice(array('model' => 'UllLocation', 'column' => 'id', 'required' => false)),
      'namespace'               => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'name'                    => new sfValidatorString(array('max_length' => 100)),
      'short_name'              => new sfValidatorString(array('max_length' => 15, 'required' => false)),
      'street'                  => new sfValidatorString(array('max_length' => 200, 'required' => false)),
      'city'                    => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'country'                 => new sfValidatorString(array('max_length' => 10, 'required' => false)),
      'post_code'               => new sfValidatorString(array('max_length' => 10, 'required' => false)),
      'phone_base_no'           => new sfValidatorString(array('max_length' => 20, 'required' => false)),
      'phone_default_extension' => new sfValidatorInteger(array('required' => false)),
      'fax_base_no'             => new sfValidatorString(array('max_length' => 20, 'required' => false)),
      'fax_default_extension'   => new sfValidatorInteger(array('required' => false)),
      'created_at'              => new sfValidatorDateTime(array('required' => false)),
      'updated_at'              => new sfValidatorDateTime(array('required' => false)),
      'creator_user_id'         => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
      'updator_user_id'         => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('ull_location[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UllLocation';
  }

}
