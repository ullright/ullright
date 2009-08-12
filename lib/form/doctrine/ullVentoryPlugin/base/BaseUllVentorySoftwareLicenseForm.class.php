<?php

/**
 * UllVentorySoftwareLicense form base class.
 *
 * @package    form
 * @subpackage ull_ventory_software_license
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseUllVentorySoftwareLicenseForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                      => new sfWidgetFormInputHidden(),
      'namespace'               => new sfWidgetFormInput(),
      'ull_ventory_software_id' => new sfWidgetFormDoctrineChoice(array('model' => 'UllVentorySoftware', 'add_empty' => false)),
      'license_key'             => new sfWidgetFormInput(),
      'quantity'                => new sfWidgetFormInput(),
      'supplier'                => new sfWidgetFormInput(),
      'delivery_date'           => new sfWidgetFormDateTime(),
      'comment'                 => new sfWidgetFormTextarea(),
      'created_at'              => new sfWidgetFormDateTime(),
      'updated_at'              => new sfWidgetFormDateTime(),
      'creator_user_id'         => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
      'updator_user_id'         => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'id'                      => new sfValidatorDoctrineChoice(array('model' => 'UllVentorySoftwareLicense', 'column' => 'id', 'required' => false)),
      'namespace'               => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'ull_ventory_software_id' => new sfValidatorDoctrineChoice(array('model' => 'UllVentorySoftware')),
      'license_key'             => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'quantity'                => new sfValidatorInteger(array('required' => false)),
      'supplier'                => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'delivery_date'           => new sfValidatorDateTime(array('required' => false)),
      'comment'                 => new sfValidatorString(array('max_length' => 4000, 'required' => false)),
      'created_at'              => new sfValidatorDateTime(array('required' => false)),
      'updated_at'              => new sfValidatorDateTime(array('required' => false)),
      'creator_user_id'         => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
      'updator_user_id'         => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('ull_ventory_software_license[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UllVentorySoftwareLicense';
  }

}
