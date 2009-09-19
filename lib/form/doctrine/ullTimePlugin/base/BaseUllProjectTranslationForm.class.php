<?php

/**
 * UllProjectTranslation form base class.
 *
 * @package    form
 * @subpackage ull_project_translation
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseUllProjectTranslationForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'name'        => new sfWidgetFormInput(),
      'description' => new sfWidgetFormTextarea(),
      'lang'        => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorDoctrineChoice(array('model' => 'UllProjectTranslation', 'column' => 'id', 'required' => false)),
      'name'        => new sfValidatorString(array('max_length' => 128)),
      'description' => new sfValidatorString(array('max_length' => 4000, 'required' => false)),
      'lang'        => new sfValidatorDoctrineChoice(array('model' => 'UllProjectTranslation', 'column' => 'lang', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('ull_project_translation[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UllProjectTranslation';
  }

}
