<?php

/**
 * UllVentoryItemAttribute form base class.
 *
 * @package    form
 * @subpackage ull_ventory_item_attribute
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseUllVentoryItemAttributeForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                 => new sfWidgetFormInputHidden(),
      'namespace'          => new sfWidgetFormInput(),
      'ull_column_type_id' => new sfWidgetFormDoctrineChoice(array('model' => 'UllColumnType', 'add_empty' => true)),
      'options'            => new sfWidgetFormTextarea(),
      'created_at'         => new sfWidgetFormDateTime(),
      'updated_at'         => new sfWidgetFormDateTime(),
      'creator_user_id'    => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
      'updator_user_id'    => new sfWidgetFormDoctrineChoice(array('model' => 'UllUser', 'add_empty' => true)),
      'slug'               => new sfWidgetFormInput(),
    ));

    $this->setValidators(array(
      'id'                 => new sfValidatorDoctrineChoice(array('model' => 'UllVentoryItemAttribute', 'column' => 'id', 'required' => false)),
      'namespace'          => new sfValidatorString(array('max_length' => 32, 'required' => false)),
      'ull_column_type_id' => new sfValidatorDoctrineChoice(array('model' => 'UllColumnType', 'required' => false)),
      'options'            => new sfValidatorString(array('max_length' => 4000, 'required' => false)),
      'created_at'         => new sfValidatorDateTime(array('required' => false)),
      'updated_at'         => new sfValidatorDateTime(array('required' => false)),
      'creator_user_id'    => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
      'updator_user_id'    => new sfValidatorDoctrineChoice(array('model' => 'UllUser', 'required' => false)),
      'slug'               => new sfValidatorString(array('max_length' => 255, 'required' => false)),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'UllVentoryItemAttribute', 'column' => array('slug')))
    );

    $this->widgetSchema->setNameFormat('ull_ventory_item_attribute[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UllVentoryItemAttribute';
  }

}
