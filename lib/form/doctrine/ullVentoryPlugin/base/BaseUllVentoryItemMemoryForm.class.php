<?php

/**
 * UllVentoryItemMemory form base class.
 *
 * @package    form
 * @subpackage ull_ventory_item_memory
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseUllVentoryItemMemoryForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                  => new sfWidgetFormInputHidden(),
      'namespace'           => new sfWidgetFormInput(),
      'ull_ventory_item_id' => new sfWidgetFormDoctrineChoice(array('model' => 'UllVentoryItem', 'add_empty' => false)),
      'transfer_at'         => new sfWidgetFormDateTime(),
      'source_ull_user_id'  => new sfWidgetFormInput(),
      'target_ull_user_id'  => new sfWidgetFormInput(),
      'comment'             => new sfWidgetFormTextarea(),
      'created_at'          => new sfWidgetFormDateTime(),
      'updated_at'          => new sfWidgetFormDateTime(),
      'creator_user_id'     => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
      'updator_user_id'     => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'id'                  => new sfValidatorDoctrineChoice(array('model' => 'UllVentoryItemMemory', 'column' => 'id', 'required' => false)),
      'namespace'           => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'ull_ventory_item_id' => new sfValidatorDoctrineChoice(array('model' => 'UllVentoryItem')),
      'transfer_at'         => new sfValidatorDateTime(),
      'source_ull_user_id'  => new sfValidatorInteger(),
      'target_ull_user_id'  => new sfValidatorInteger(),
      'comment'             => new sfValidatorString(array('max_length' => 4000, 'required' => false)),
      'created_at'          => new sfValidatorDateTime(array('required' => false)),
      'updated_at'          => new sfValidatorDateTime(array('required' => false)),
      'creator_user_id'     => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
      'updator_user_id'     => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('ull_ventory_item_memory[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UllVentoryItemMemory';
  }

}
