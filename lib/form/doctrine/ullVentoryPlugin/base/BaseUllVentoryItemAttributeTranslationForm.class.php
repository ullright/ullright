<?php

/**
 * UllVentoryItemAttributeTranslation form base class.
 *
 * @package    form
 * @subpackage ull_ventory_item_attribute_translation
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseUllVentoryItemAttributeTranslationForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'   => new sfWidgetFormInputHidden(),
      'name' => new sfWidgetFormInput(),
      'help' => new sfWidgetFormTextarea(),
      'lang' => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'id'   => new sfValidatorDoctrineChoice(array('model' => 'UllVentoryItemAttributeTranslation', 'column' => 'id', 'required' => false)),
      'name' => new sfValidatorString(array('max_length' => 128)),
      'help' => new sfValidatorString(array('max_length' => 4000, 'required' => false)),
      'lang' => new sfValidatorDoctrineChoice(array('model' => 'UllVentoryItemAttributeTranslation', 'column' => 'lang', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('ull_ventory_item_attribute_translation[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UllVentoryItemAttributeTranslation';
  }

}
