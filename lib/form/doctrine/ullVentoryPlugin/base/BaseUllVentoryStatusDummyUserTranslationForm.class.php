<?php

/**
 * UllVentoryStatusDummyUserTranslation form base class.
 *
 * @package    form
 * @subpackage ull_ventory_status_dummy_user_translation
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseUllVentoryStatusDummyUserTranslationForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'display_name' => new sfWidgetFormInput(),
      'lang'         => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorDoctrineChoice(array('model' => 'UllVentoryStatusDummyUserTranslation', 'column' => 'id', 'required' => false)),
      'display_name' => new sfValidatorString(array('max_length' => 64, 'required' => false)),
      'lang'         => new sfValidatorDoctrineChoice(array('model' => 'UllVentoryStatusDummyUserTranslation', 'column' => 'lang', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('ull_ventory_status_dummy_user_translation[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UllVentoryStatusDummyUserTranslation';
  }

}
