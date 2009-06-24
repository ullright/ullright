<?php

/**
 * UllVentoryItem form base class.
 *
 * @package    form
 * @subpackage ull_ventory_item
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseUllVentoryItemForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                        => new sfWidgetFormInputHidden(),
      'namespace'                 => new sfWidgetFormInput(),
      'inventory_number'          => new sfWidgetFormInput(),
      'serial_number'             => new sfWidgetFormInput(),
      'comment'                   => new sfWidgetFormTextarea(),
      'ull_ventory_item_model_id' => new sfWidgetFormDoctrineChoice(array('model' => 'UllVentoryItemModel', 'add_empty' => false)),
      'ull_entity_id'             => new sfWidgetFormDoctrineChoice(array('model' => 'UllEntity', 'add_empty' => false)),
      'created_at'                => new sfWidgetFormDateTime(),
      'updated_at'                => new sfWidgetFormDateTime(),
      'creator_user_id'           => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
      'updator_user_id'           => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'id'                        => new sfValidatorDoctrineChoice(array('model' => 'UllVentoryItem', 'column' => 'id', 'required' => false)),
      'namespace'                 => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'inventory_number'          => new sfValidatorString(array('max_length' => 128)),
      'serial_number'             => new sfValidatorString(array('max_length' => 128, 'required' => false)),
      'comment'                   => new sfValidatorString(array('max_length' => 4000, 'required' => false)),
      'ull_ventory_item_model_id' => new sfValidatorDoctrineChoice(array('model' => 'UllVentoryItemModel')),
      'ull_entity_id'             => new sfValidatorDoctrineChoice(array('model' => 'UllEntity')),
      'created_at'                => new sfValidatorDateTime(array('required' => false)),
      'updated_at'                => new sfValidatorDateTime(array('required' => false)),
      'creator_user_id'           => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
      'updator_user_id'           => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'UllVentoryItem', 'column' => array('inventory_number')))
    );

    $this->widgetSchema->setNameFormat('ull_ventory_item[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UllVentoryItem';
  }

}
