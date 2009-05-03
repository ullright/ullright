<?php

/**
 * UllEmploymentTypeTranslation form base class.
 *
 * @package    form
 * @subpackage ull_employment_type_translation
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 8508 2008-04-17 17:39:15Z fabien $
 */
class BaseUllEmploymentTypeTranslationForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'   => new sfWidgetFormInputHidden(),
      'name' => new sfWidgetFormInput(),
      'lang' => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(array(
      'id'   => new sfValidatorDoctrineChoice(array('model' => 'UllEmploymentTypeTranslation', 'column' => 'id', 'required' => false)),
      'name' => new sfValidatorString(array('max_length' => 100)),
      'lang' => new sfValidatorDoctrineChoice(array('model' => 'UllEmploymentTypeTranslation', 'column' => 'lang', 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('ull_employment_type_translation[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    parent::setup();
  }

  public function getModelName()
  {
    return 'UllEmploymentTypeTranslation';
  }

}
