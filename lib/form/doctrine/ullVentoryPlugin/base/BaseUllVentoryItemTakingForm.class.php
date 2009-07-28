<?php

/**
 * UllVentoryItemTaking form base class.
 *
 * @package    form
 * @subpackage ull_ventory_item_taking
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseUllVentoryItemTakingForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                    => new sfWidgetFormInputHidden(),
      'namespace'             => new sfWidgetFormInput(),
      'ull_ventory_item_id'   => new sfWidgetFormDoctrineChoice(array('model' => 'UllVentoryItem', 'add_empty' => false)),
      'ull_ventory_taking_id' => new sfWidgetFormDoctrineChoice(array('model' => 'UllVentoryTaking', 'add_empty' => false)),
      'created_at'            => new sfWidgetFormDateTime(),
      'updated_at'            => new sfWidgetFormDateTime(),
      'creator_user_id'       => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
      'updator_user_id'       => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'id'                    => new sfValidatorDoctrineChoice(array('model' => 'UllVentoryItemTaking', 'column' => 'id', 'required' => false)),
      'namespace'             => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'ull_ventory_item_id'   => new sfValidatorDoctrineChoice(array('model' => 'UllVentoryItem')),
      'ull_ventory_taking_id' => new sfValidatorDoctrineChoice(array('model' => 'UllVentoryTaking')),
      'created_at'            => new sfValidatorDateTime(array('required' => false)),
      'updated_at'            => new sfValidatorDateTime(array('required' => false)),
      'creator_user_id'       => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
      'updator_user_id'       => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('ull_ventory_item_taking[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UllVentoryItemTaking';
  }

}
