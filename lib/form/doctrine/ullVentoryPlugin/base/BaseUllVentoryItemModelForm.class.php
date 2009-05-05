<?php

/**
 * UllVentoryItemModel form base class.
 *
 * @package    form
 * @subpackage ull_ventory_item_model
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseUllVentoryItemModelForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                               => new sfWidgetFormInputHidden(),
      'namespace'                        => new sfWidgetFormInput(),
      'name'                             => new sfWidgetFormInput(),
      'ull_ventory_item_manufacturer_id' => new sfWidgetFormDoctrineChoice(array('model' => 'UllVentoryItemManufacturer', 'add_empty' => false)),
      'ull_ventory_item_type_id'         => new sfWidgetFormDoctrineChoice(array('model' => 'UllVentoryItemType', 'add_empty' => false)),
      'created_at'                       => new sfWidgetFormDateTime(),
      'updated_at'                       => new sfWidgetFormDateTime(),
      'creator_user_id'                  => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
      'updator_user_id'                  => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'id'                               => new sfValidatorDoctrineChoice(array('model' => 'UllVentoryItemModel', 'column' => 'id', 'required' => false)),
      'namespace'                        => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'name'                             => new sfValidatorString(array('max_length' => 128)),
      'ull_ventory_item_manufacturer_id' => new sfValidatorDoctrineChoice(array('model' => 'UllVentoryItemManufacturer')),
      'ull_ventory_item_type_id'         => new sfValidatorDoctrineChoice(array('model' => 'UllVentoryItemType')),
      'created_at'                       => new sfValidatorDateTime(array('required' => false)),
      'updated_at'                       => new sfValidatorDateTime(array('required' => false)),
      'creator_user_id'                  => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
      'updator_user_id'                  => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('ull_ventory_item_model[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UllVentoryItemModel';
  }

}
